<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePostToTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('post_to_tags'))
        {
            Schema::create('post_to_tags', function (Blueprint $table)
            {
                $table->increments('id');
                $table->integer('tagId', 20)->unsigned();
                $table->integer('postId')->unsigned();
                $table->foreign('tagId')->references('id')->on('tags');
                $table->foreign('postId')->references('id')->on('posts');
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
        Schema::dropIfExists('post_to_tags');
    }
}
