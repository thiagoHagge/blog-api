<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_news', function (Blueprint $table) {
            $table->id('news_id');
            $table->string('news_slug');
            $table->string('news_title');
            $table->string('news_image')->nullable();
            $table->string('news_author')->nullable();
            $table->string('news_ytId')->nullable();
            $table->string('news_podcast')->nullable();
            $table->text('news_content');
            $table->dateTime('news_creation');
            $table->dateTime('news_updated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('front_news');
    }
}
