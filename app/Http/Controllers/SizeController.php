<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sizes;

class SizeController extends Controller
{
    //
    public function index() {
        return Sizes::orderBy('id', 'desc')->get();
    }

    public function create(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:colors',
        ]);

        return Sizes::create([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function edit(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:sizes,name,'.$request->id,
        ]);

        $table  = Sizes::find($request->id);
        $table->name = $request->name;
        $table->updated_at = now();
        $table->save();
        return $table;
    }
    

    public function delete($id) {
        $table = Sizes::find($id)->delete();
        return ['message' => 'success'];
    }
}
