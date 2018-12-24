<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('posts')) 
        {
            Schema::create('posts', function (Blueprint $table)
            {
                $table->increments('id');
                $table->string('text');
                $table->integer('articleId')->unique();
                $table->dateTime('created_at');
            });
        }

        if(!Schema::hasTable('tags'))
        {
            Schema::create('tags', function (Blueprint $table)
            {
                $table->increments('id');
                $table->string('name');
            });
        }        

     }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('posts');
    }
}
