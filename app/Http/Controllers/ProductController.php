<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
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
            'color' => 'required',
            'size' => 'required',
        ]);

        if($request->hasFile('img')){
            $fileName = time().'.'.$request->file('img')->getClientOriginalExtension();
            $request->file('img')->move(public_path('images'), $fileName);
            $product = new Products;
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->avatar = $fileName;
            $product->price = $request->price;
            $product->color = $request->color;
            $product->size = $request->size;
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
                'color' => $product->color,
                'size' => $product->size,
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
        }else if($request->colorOrSize != ''){
            $params['body']['query'] =  [
                "bool" => [
                    "should" => [
                        ['match' => ['color' => $request->colorOrSize]],
                        ['match' => ['size' => $request->colorOrSize]],
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
        $limit = 3;
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
        $Product = Products::find($id);
        return response()->json([
            $this->responseDataDetail($Product)
        ], 200);
    }

    public function responseDataDetail($Product){
        $data = [];
        $data['product_detail']['id'] = $Product->id; 
        $data['product_detail']['category_id'] = $Product->category_id;
        $Category = Categories::find($Product->category_id); 
        $data['product_detail']['category_name'] = $Category->name; 
        $data['product_detail']['name'] = $Product->name; 
        $data['product_detail']['avatar'] = $Product->avatar; 
        $data['product_detail']['price'] = $Product->price; 
        $data['product_detail']['color'] = $Product->color; 
        $data['product_detail']['size'] = $Product->size; 
        $data['product_detail']['created_at'] = $Product->created_at; 
        $data['product_detail']['updated_at'] = $Product->updated_at; 

        $data['product_ralate'] = [];
        $product_ralate = Products::where('category_id', $Category->id)->where('id', '<>', $Product->id)->limit(4)->orderBy('id', 'DESC')->get();
        $data_product_ralate = $this->addProductRalate($product_ralate);

        $count_data = count($data_product_ralate); 
        $data_product_more = [];
        if($count_data < 4) {
            $limit = 4 - $count_data;          
            $product_more = Products::where('category_id', '<>', $Category->id)->limit($limit)->orderBy('id', 'DESC')->get();
            $data_product_more = $this->addProductRalate($product_more);
        }
        $data['product_ralate'] = array_merge($data_product_ralate, $data_product_more);
        return  $data;
    }  


    public function addProductRalate($product_ralate) {
        $data = [];
        foreach($product_ralate as $key => $item) {
            $data[$key]['id'] = $item->id;
            $data[$key]['category_id'] = $item->category_id;
            $Category = Categories::find($item->category_id); 
            $data[$key]['category_name'] = $Category->name;
            $data[$key]['name'] = $item->name;
            $data[$key]['avatar'] = $item->avatar;
            $data[$key]['price'] = $item->price;
            $data[$key]['color'] = $item->color;
            $data[$key]['size'] = $item->size;
            $data[$key]['created_at'] = $item->created_at;
            $data[$key]['updated_at'] = $item->updated_at;
        }
        return $data;
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
        $product->color = $request->color;
        $product->size = $request->size;
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
                'color' => $product->color,
                'size' => $product->size,
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
