<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFilePath = public_path('data/likes.json');
        $jsonData = File::get($jsonFilePath);

        $likes = json_decode($jsonData, true);

        DB::table('likes')->insert($likes);
    }
}
