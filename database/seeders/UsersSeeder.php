<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $jsonFilePath = public_path('data/users.json');
        $jsonData = File::get($jsonFilePath);

        $users = json_decode($jsonData, true);

        foreach ($users as &$user) {
            $user['password'] = Hash::make($user['email']);
        }

        DB::table('users')->insert($users);
    }
}
