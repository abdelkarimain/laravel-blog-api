<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('like')->default(0);
            $table->smallInteger('dislike')->default(0);
            $table->integer('post_id');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
