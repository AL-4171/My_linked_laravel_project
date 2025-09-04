<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('posts_categories', function (Blueprint $table) {
       $table->bigIncrements('ID');
            $table->unsignedBigInteger('PostID');
            $table->unsignedBigInteger('CategoryID');
            $table->timestamps();

            $table->foreign('PostID')->references('PostID')->on('posts')->onDelete('cascade');
            $table->foreign('CategoryID')->references('CategoryID')->on('categories')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_categories');
    }
}
