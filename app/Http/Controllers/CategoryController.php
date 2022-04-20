<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Products;
use Elasticsearch\ClientBuilder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $client;
    protected $index;
    protected $type;
    public function __construct() {
        $this->index = 'categories';
        $this->type =  '_doc';   
        $this->client = ClientBuilder::create()->build();
        $exists = $this->client->indices()->exists(['index' => $this->index]);
        if(!$exists)
            $this->client->indices()->create(['index' => $this->index]);
    }
    public function index()
    {
        //
        $categories = Categories::all();
        $data = [];
        foreach($categories as $key => $item){
            $data[$key]['id'] = $item['id'];
            $data[$key]['name'] = $item['name'];
        }
        return response()->json([
            $data
        ], 200);;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required|unique:categories',
        ]);
        $categories = new Categories;
        $categories->name =  $request->name;
        $categories->created_at = now();
        $categories->updated_at = now();
        if($categories->save()){
            $params = [
                "index" => $this->index,
                "type" => $this->type,
                'id' => $categories->id,
                'body' => [
                    'id' => $categories->id,
                    'name' => $request->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];
            $res = $this->client->index($params);
            return $params['body'];
        }
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
            ]
        ];
        $search = [];
        $get_page = $request->get('page');
        $page = isset($get_page) ? $get_page : 1;
        $search['status'] = false; 
        if($request->search != ''){
            $params = [
                "index" => $this->index,
                "type" => $this->type,
                "body" => [
                    "query" => [
                        "bool" => [
                            "should" => [
                                ['match' => ['name' => $request->search]],
                            ]
                        ]
                    ],
                    'sort' => [
                        'id' => [
                            'order' => 'desc'
                        ]
                    ]
                ]
            ];
            $search['text'] = $request->search; 
            $search['status'] = true; 
        }
        $res = $this->client->search($params);
        $datas = $res['hits']['hits'];
        $total = $res['hits']['total']['value'];
        return $this->reponseDataPaginate($datas, $total, $page, $search);
    }

    public function reponseDataPaginate($datas, $total, $page, $search){
        $limit = 3;
        $from = ($page - 1) * $limit;
        $total_page =  ceil($total / $limit);
        if($search['status']){
            $params = [
                "index" => $this->index,
                "type" => $this->type,
                "size" => $limit,
                "from" => $from,
                "body" => [
                    "query" => [
                        "bool" => [
                            "should" => [
                                ['match' => ['name' => $search['text']]],
                            ]
                        ]
                    ],
                    'sort' => [
                        'id' => [
                            'order' => 'desc'
                        ]
                    ]
                ]
            ];
        }else{
            $params = [
                "index" => $this->index,
                "type" => $this->type,
                "size" => $limit,
                "from" => $from,
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
        }
        $res = $this->client->search($params);
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
        //
        return Categories::find($id);
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
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required|unique:categories,name,'.$request->id,
        ]);
        $category = Categories::find($request->id);
        if($category) {
            $category->name = $request->name;
            $category->updated_at = now();
            if($category->save()){
                $params = [
                    "index" => $this->index,
                    "type" => $this->type,
                    'id' => $request->id,
                    'body' => [
                        'id' => $request->id,
                        'name' => $request->name,
                        'created_at' => $category->created_at,
                        'updated_at' => now(),
                    ]
                ];
                $res = $this->client->index($params);
                $product =  Products::where('category_id', $category->id)->get();
                foreach($product as $item) {
                    $params_product = [
                        "index" => 'products2',
                        "type" => '_doc',
                        'id' => $item->id,
                        'body' => [
                            'id' => $item->id,
                            'category_id' => $item->category_id,
                            'category_name' => $category->name,
                            'name' => $item->name,
                            'avatar' => $item->avatar,
                            'price' => $item->price,
                            'color' => $item->color,
                            'size' => $item->size,
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                        ]
                    ];
                    $res = $this->client->index($params_product);
                }
                return $params['body'];
            }
        }        
        return response()->json(['error' => 'category not found'], 402);
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
        $categories = Categories::where('id', $id)->first();
        $product  = Products::where('category_id', $categories->id)->get();
        foreach($product as $item){
            unlink("images/".$item->avatar);
            $params_product = [
                'index' => 'products2',
                'id'    => $item->id
            ];
            $this->client->delete($params_product);
        }
        $categories->delete();
        $params = [
            'index' => $this->index,
            'id'    => $id
        ];
        $this->client->delete($params);
        return response()->json([
            'mesage' => 'Delete category success'
        ], 200);
    }
}
