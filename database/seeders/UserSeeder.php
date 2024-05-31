<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')
            ->truncate();

        $user = new User();
        $user->id = 1;
        $user->name = 'Gary';
        $user->email = 'gary@roave.com';
        $user->password = Hash::make('password');

        $user->save();
    }
}
