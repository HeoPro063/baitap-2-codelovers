<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('products')->insert([
            'category_id' => 2,
            'name' => Str::random(10),
            'avatar' => '',
            'price' => 10000,
            'color' => 'red',
            'size' => 'l',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
