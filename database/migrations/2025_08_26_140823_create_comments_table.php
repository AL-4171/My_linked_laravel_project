<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('comments', function (Blueprint $table) {
                $table->bigIncrements('CommentID');
            $table->string('Content', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('PostID');
            $table->timestamps();

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('PostID')->references('PostID')->on('posts')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
