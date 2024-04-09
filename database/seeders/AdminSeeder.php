<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email','admin@test.com')->first();
        if(!$user){
            $user = new User();
            $user->name = 'admin';
            $user->email = 'admin@test.com';
            $user->password='$2y$10$Tgj3K14samGowb4KVUeEeug9sAvj4eewOsuBnmPiUKwOSsYkI7mju';
            $user->created_at= now();
            $user->updated_at= now();
            $user->save();
        }
    }

}
