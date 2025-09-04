<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
   
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('UserID');
            $table->string('Name', 100);
            $table->string('Email', 100)->unique();
            $table->string('pass');
            $table->integer('Age')->nullable();
            $table->string('Phone', 20)->nullable();
            $table->enum('Role', ['user','admin'])->default('user');
            $table->timestamps();
});

    }

   
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
