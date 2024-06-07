<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFilePath = public_path('data/posts.json');
        $jsonData = File::get($jsonFilePath);

        $posts = json_decode($jsonData, true);

        DB::table('posts')->insert($posts);
    }
}
