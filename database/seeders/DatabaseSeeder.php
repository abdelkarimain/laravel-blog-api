<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $posts = [];

        // Generate 20 unique posts
        for ($i = 0; $i < 20; $i++) {
            $posts[] = [
                'userId' => 1,
                'content' => $faker->paragraphs(3, true),
                'title' => $faker->sentence,
                'slug' => $faker->slug,
                'image' => "https://www.hostinger.com/tutorials/wp-content/uploads/sites/2/2021/09/how-to-write-a-blog-post.png",
                'category' => "javascript",
                'premium' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert posts into the database
        DB::table('posts')->insert($posts);
    }
}
