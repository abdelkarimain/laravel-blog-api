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
        for ($i = 0; $i < 18; $i++) {
            $posts[] = [
                'userId' => 1,
                'content' => $faker->paragraphs(5, true),
                'title' => $faker->sentence,
                'slug' => $faker->slug,
                'image' => "https://miro.medium.com/v2/resize:fit:1400/1*5zbjAY6BL_u-OXF0_ZAPtw.jpeg",
                'category' => $faker->randomElement(['javascript', 'php', 'laravel', 'python']),
                'premium' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert posts into the database
        DB::table('posts')->insert($posts);
    }
}


// <p>Hey folks! 👋 Let's embark on a journey into the realm of JavaScript, the language that powers the dynamic web. Whether you're a seasoned developer or just starting out, JavaScript's versatility and simplicity make it an exciting language to work with. Let's explore some code examples to understand its magic better!</p><p><br></p><p><strong>1) Hello, World! 🌍</strong></p><p><br></p><pre class="ql-syntax" spellcheck="false">console.log("Hello, World!");
// </pre><p><br></p><p><br></p><p><br></p><blockquote><em>Ah, the classic! This simple line of code prints "Hello, World!" to the console. It's the first step for many developers diving into JavaScript.</em></blockquote><p><br></p><pre class="ql-syntax" spellcheck="false">let name = "Alice";
// let age = 30;
// let isStudent = true;
// </pre><p><br></p><pre class="ql-syntax" spellcheck="false">console.log(Name: ${name}, Age: ${age}, Student: ${isStudent});
// </pre><p>Here, we're declaring variables to store a name (a string), age (a number), and a boolean indicating if someone is a student. The backticks (`) allow us to interpolate variables directly into the string.</p><p><br></p><p><strong>2) Arrays and Loops 🔄</strong></p><p><br></p><pre class="ql-syntax" spellcheck="false">let fruits = ["Apple", "Banana", "Orange"];

// for (let i = 0; i &lt; fruits.length; i++) {
// &nbsp;console.log(fruits[i]);
// }
// </pre><p><br></p><p><br></p><p><br></p><blockquote><em>Arrays are collections of data, and loops help us iterate over them. Here, we have an array of fruits, and a for loop to print each fruit.</em></blockquote><p><br></p><p><strong>3) DOM Manipulation 🖥</strong></p><p><br></p><pre class="ql-syntax" spellcheck="false">&lt;!DOCTYPE html&gt;
// &lt;html&gt;
// &lt;head&gt;
// &nbsp;&lt;title&gt;DOM Manipulation&lt;/title&gt;
// &lt;/head&gt;
// &lt;body&gt;
// &nbsp;&lt;div id="message"&gt;&lt;/div&gt;

// &nbsp;&lt;script&gt;
// &nbsp;&nbsp;document.getElementById("message").innerText = "Hello, DOM!";
// &nbsp;&lt;/script&gt;
// &lt;/body&gt;
// &lt;/html&gt;
// </pre><p><br></p><p><br></p><p><br></p><blockquote><em>JavaScript isn't just for the console! With the Document Object Model (DOM), we can manipulate HTML elements dynamically. This code changes the text content of a &lt;div&gt; element with the ID "message".</em></blockquote><p><br></p><p>JavaScript's possibilities are endless, from creating interactive websites to building complex web applications. These examples scratch the surface of what you can achieve with this powerful language. So keep coding, exploring, and pushing the boundaries of what's possible with JavaScript! 🚀✨</p>

