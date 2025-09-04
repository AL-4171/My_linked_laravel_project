<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_tags', function (Blueprint $table) {
              $table->bigIncrements('ID');
            $table->unsignedBigInteger('PostID');
            $table->unsignedBigInteger('TagID');
            $table->timestamps();

            $table->foreign('PostID')->references('PostID')->on('posts')->onDelete('cascade');
            $table->foreign('TagID')->references('TagID')->on('tags')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_tags');
    }
}
