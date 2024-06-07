<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PostsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(CommentsSeeder::class);
        $this->call(LikesTableSeeder::class);
    }
}


