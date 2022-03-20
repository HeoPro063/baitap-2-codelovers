<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Products::all();
        return response()->json([
            $this->responseGetProduct($data)    
        ], 200);
    }

    public function responseGetProduct($data){
        $list = [];
        foreach($data as $key => $value){
            $list[$key]['id'] = $value->id;
            $dataCategory = Categories::find($value->category_id);
            $list[$key]['category_id'] = $dataCategory->id;
            $list[$key]['nameCategory'] = $dataCategory->name;
            $list[$key]['name'] = $value->name;
            $list[$key]['avatar'] = $value->avatar;
            $list[$key]['price'] = $value->price;
            $list[$key]['color'] = $value->color;
            $list[$key]['size'] = $value->size;
        }
        return $list;
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        $this->validate($request, [
            'categoryId' => 'required',
            'name' => 'required',
            'price' => 'required',
            'color' => 'required',
            'size' => 'required',
        ]);
        if($request->hasFile('img')){
            $fileName = time().'.'.$request->file('img')->getClientOriginalExtension();
            $request->file('img')->move(public_path('images'), $fileName);
            $product = new Products;
            $product->category_id = $request->categoryId;
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
        $data = [];
        $data['id'] = $product->id;
        $dataCategory = Categories::find($product->category_id);
        $data['category_id'] = $dataCategory->id;
        $data['nameCategory'] = $dataCategory->name;
        $data['name'] = $product->name;
        $data['avatar'] = $product->avatar;
        $data['price'] = $product->price;
        $data['color'] = $product->color;
        $data['size'] = $product->size;
        return $data;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $query = DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.*','categories.name AS nameCategory');
        if($request->search != ''){
            $query->where(function ($q) use ($request) {
                $q->orWhere('products.name', 'like', $request->search.'%');
            });
        }
        return $dataPaginate =  $query->orderBy('id', 'desc')->paginate(3);
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
        if($request->hasFile('img')){
            $fileName = time().'.'.$request->file('img')->getClientOriginalExtension();
            $request->file('img')->move(public_path('images'), $fileName);
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
        $data = [];
        $data['id'] = $product->id;
        $data['category_id'] = $product->category_id;
        $category = Categories::find($product->category_id);
        $data['nameCategory'] = $category->name;
        $data['name'] = $product->name;
        $data['avatar'] = $product->avatar;
        $data['price'] = $product->price;
        $data['color'] = $product->color;
        $data['size'] = $product->size;
        return $data;
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
        $product = Products::find($id);
        unlink("images/".$product->avatar);
        $product->delete();
        return response()->json([
            'delete success'
        ], 200);
    }
}
