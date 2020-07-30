<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $user = User::where('email','bhumitpatel58@gmail.com')->first();

       if(!$user)
       {
           User::create([

            'name' => 'Bhumit Patel',
            'email' => 'bhumitpatel58@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('bhumit123')

           ]);
       }
    }
}
