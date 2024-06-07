<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFilePath = public_path('data/comments.json');
        $jsonData = File::get($jsonFilePath);

        $comments = json_decode($jsonData, true);

        DB::table('comments')->insert($comments);
    }
}
