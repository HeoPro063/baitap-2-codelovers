<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        return Categories::create([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $query = Categories::select('id', 'name');
        if($request->search != ''){
            $query->where(function ($q) use ($request) {
                $q->orWhere('name', 'like', $request->search.'%');
            });
        }
        return $query->orderBy('id', 'desc')->paginate(3);
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
        $category->name = $request->name;
        $category->updated_at = now();
        $category->save();
        return $category;
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
        Categories::where('id', $id)->delete();
        return response()->json([
            'mesage' => 'Delete category success'
        ], 200);
    }
}
