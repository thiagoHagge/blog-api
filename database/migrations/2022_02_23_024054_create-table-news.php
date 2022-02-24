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
            $table->string('crsl_subtitle');
            $table->text('news_content');
            $table->string('news_image');
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
