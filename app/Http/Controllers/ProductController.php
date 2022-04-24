<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Sizes;
use App\Models\Colors;
use Illuminate\Support\Facades\DB;
use Elasticsearch\ClientBuilder;

class ProductController extends Controller
{


    protected $client;
    protected $index;
    protected $type;
    public function __construct() {
        $this->index = 'products2';
        $this->type =  '_doc';   
        $this->client = ClientBuilder::create()->build();
        $exists = $this->client->indices()->exists(['index' => $this->index]);
        if(!$exists)
            $this->client->indices()->create(['index' => $this->index]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        $this->validate($request, [
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'color_id' => 'required',
            'size_id' => 'required',
        ]);

        if($request->hasFile('img')){
            $fileName = time().'.'.$request->file('img')->getClientOriginalExtension();
            $request->file('img')->move(public_path('images'), $fileName);
            $product = new Products;
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->avatar = $fileName;
            $product->price = $request->price;
            $product->color_id = $request->color_id;
            $product->size_id = $request->size_id;
            $product->created_at = now();
            $product->updated_at = now();
            if($product->save()){
                return response()->json([
                    $this->responseDataAdd($product->id)
                ], 200);
            }
        }else{
            return response()->json([
                'required img'
            ], 422);             
        }
        return $request->name;
    }
    public function responseDataAdd($id){
        $product = Products::find($id);
        $category = Categories::find($product->category_id);
        $params = [
            "index" => $this->index,
            "type" => $this->type,
            'id' => $product->id,
            'body' => [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'category_name' => $category->name,
                'name' => $product->name,
                'avatar' => $product->avatar,
                'price' => $product->price,
                'color_id' => $product->color_id,
                'size_id' => $product->size_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        $res = $this->client->index($params);
        return $params['body'];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = [
            "index" => $this->index,
            "type" => $this->type,
            "body" => [
                "query" => [
                    "match_all" => (object)[],
                ],
                'sort' => [
                    'id' => [
                        'order' => 'desc'
                    ]
                ]
            ]
        ];
        $get_page = $request->get('page');
        $page = isset($get_page) ? $get_page : 1;
        if($request->search != ''){
            $params['body']['query'] =  [
                'bool' => [
                    "should" => [
                        ['match' => ['name' => $request->search]],
                    ]
                ]
            ];
        }else if($request->idColor != ''){
            $params['body']['query'] =  [
                "bool" => [
                    "should" => [
                        ['match' => ['color_id' => $request->idColor]],
                    ]
                ]
            ];
        }else if($request->idSize != ''){
            $params['body']['query'] =  [
                "bool" => [
                    "should" => [
                        ['match' => ['size_id' => $request->idSize]],
                    ]
                ]
            ];
        }else if($request->idProduct != ''){
            $params['body']['query'] =  [
                "bool" => [
                    "should" => [
                        ['match' => ['id' => $request->idProduct]],
                    ]
                ]
            ];
        }else if($request->idCategory != ''){
            $params['body']['query'] =  [
                "bool" => [
                    "should" => [
                        ['match' => ['category_id' => $request->idCategory]],
                    ]
                ]
            ];
        }
        $res = $this->client->search($params);
        $total = $res['hits']['total']['value'];
        return $this->reponseDataPaginate($total, $page, $params);
    }

    public function reponseDataPaginate($total, $page, $params){
        $limit = 5;
        $from = ($page - 1) * $limit;
        $total_page =  ceil($total / $limit);
        $params['size'] = $limit;
        $params['from'] = $from;
        $res = $this->client->search($params);
        if($res['hits']['hits'] == []){
            $params['from'] = 0;
            $res = $this->client->search($params);
        }
        $result = [];
        foreach($res['hits']['hits'] as $key => $item){
            $result['data_hits'][$key] =  $item['_source'];
        }
        $result['total_page'] = $total_page;
        $result['page'] = $page;
        $result['limit'] = $limit;
        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $Product = Products::find($id);

        $params = [
            'index' => $this->index,
            'id'    => $id
        ];
        $response = $this->client->get($params);
        return response()->json([
            $this->responseDataDetail($response['_source'])
        ], 200);
    }

    public function responseDataDetail($product){
        $data = [];
        $data['product_detail']['id'] = $product['id'];
        $data['product_detail']['category_id'] = $product['category_id'];
        $data['product_detail']['category_name'] = $product['category_name'];
        $data['product_detail']['name'] = $product['name'];
        $data['product_detail']['price'] = $product['price'];
        $data['product_detail']['color_id'] = $product['color_id'];
        $data['product_detail']['size_id'] = $product['size_id'];
        $Colors = Colors::find($product['color_id']);
        $Sizes = Sizes::find($product['size_id']);
        $data['product_detail']['color_id'] = $product['color_id'];
        $data['product_detail']['color_id'] = $product['color_id'];
        $data['product_detail']['color'] = $Colors->name;
        $data['product_detail']['size'] = $Sizes->name;
        $name_first = explode(' ', $product['name']);
        $params = [
            "index" => $this->index,
            "type" => $this->type,
            "size" => 4,
            "body" => [
                "query" => [
                    "bool" => [
                        "should" => [
                            [
                                "match_phrase_prefix" => [
                                    "name" => $name_first[0]
                                ],
                            ],
                        ],
                        "must_not" => [
                            [
                                "match" => [
                                    "id" =>  $product['id']
                                ]
                            ],
                        ],
                    ]
                ],
                'sort' => [
                    'id' => [
                        'order' => 'desc'
                    ]
                ]
            ]
        ];
        // return $params;
        $response = $this->client->search($params);
        $data_product_ralate = $response['hits']['hits'];
        $data['product_ralate'] = [];
        foreach($data_product_ralate as $key => $item) {
            $data['product_ralate'][$key]['id'] = $item['_source']['id'];
            $data['product_ralate'][$key]['category_id'] = $item['_source']['category_id'];
            $data['product_ralate'][$key]['category_name'] = $item['_source']['category_name'];
            $data['product_ralate'][$key]['name'] = $item['_source']['name'];
            $data['product_ralate'][$key]['avatar'] = $item['_source']['avatar'];
            $data['product_ralate'][$key]['price'] = $item['_source']['price'];
            $data['product_ralate'][$key]['color_id'] = $item['_source']['color_id'];
            $data['product_ralate'][$key]['size_id'] = $item['_source']['size_id'];
            $Colors = Colors::find($item['_source']['color_id']);
            $Sizes = Sizes::find($item['_source']['size_id']);
            $data['product_ralate'][$key]['color'] = $Colors->name; 
            $data['product_ralate'][$key]['size'] = $Sizes->name; 
            $data['product_ralate'][$key]['created_at'] = $item['_source']['created_at'];
            $data['product_ralate'][$key]['updated_at'] = $item['_source']['updated_at'];
        }
        return  $data;
    }  

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        $product = Products::find($request->id);
        if($request->hasFile('img_edit')){
            $fileName = time().'.'.$request->file('img_edit')->getClientOriginalExtension();
            $request->file('img_edit')->move(public_path('images'), $fileName);
            unlink("images/".$product->avatar);
            $product->avatar = $fileName;
        }
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->color_id = $request->color_id;
        $product->size_id = $request->size_id;
        $product->updated_at = now();
        if($product->save()){
            return response()->json([
                $this->responseDataEditProduct($product->id)
            ], 200);
        }
    
    }


    public function responseDataEditProduct($id){
        $product = Products::find($id);
        $category = Categories::find($product->category_id);
        $params = [
            "index" => $this->index,
            "type" => $this->type,
            'id' => $product->id,
            'body' => [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'category_name' => $category->name,
                'name' => $product->name,
                'avatar' => $product->avatar,
                'price' => $product->price,
                'color_id' => $product->color_id,
                'size_id' => $product->size_id,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ]
        ];
        $res = $this->client->index($params);
        return $params['body'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $params = [
            'index' => $this->index,
            'id'    => $id
        ];
        $this->client->delete($params);
        $product = Products::find($id);
        unlink("images/".$product->avatar);
        $product->delete();
        return response()->json([
            'delete success'
        ], 200);
    }
}
