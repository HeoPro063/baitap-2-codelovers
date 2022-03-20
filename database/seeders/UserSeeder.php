<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $table = new User;
        $table->name = 'Nguyen Phi Hao';
        $table->email = 'hao@gmail.com';
        $table->password = Hash::make('123123');
        $table->save();
    }
}
