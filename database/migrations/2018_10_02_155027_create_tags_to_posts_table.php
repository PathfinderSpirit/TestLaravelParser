<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('tags_to_posts'))
        {
            Schema::create('tags_to_posts', function (Blueprint $table)
            {
                $table->integer('tagId', 10)->unsigned();
                $table->integer('postId', 10)->unsigned();
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
        Schema::dropIfExists('tags_to_posts');
    }
}
