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
                $table->increments('id')->unsigned();
                $table->integer('tag_ref')->unsigned();
                $table->integer('post_ref')->unsigned();
                $table->foreign('tag_ref')->references('id')->on('tags');
                $table->foreign('post_ref')->references('id')->on('posts');
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
