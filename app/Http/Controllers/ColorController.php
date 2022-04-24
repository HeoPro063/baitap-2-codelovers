<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colors;
class ColorController extends Controller
{
    //
    public function index() {
        return Colors::orderBy('id', 'desc')->get();
    }

    public function create(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:colors',
        ]);

        return Colors::create([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function edit(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:colors,name,'.$request->id,
        ]);

        $table  = Colors::find($request->id);
        $table->name = $request->name;
        $table->updated_at = now();
        $table->save();
        return $table;
    }
    

    public function delete($id) {
        $table = Colors::find($id)->delete();
        return ['message' => 'success'];
    }

}
