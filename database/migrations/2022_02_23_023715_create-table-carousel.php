<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCarousel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_carousel', function (Blueprint $table) {
            $table->id('crsl_id');
            $table->string('crsl_title');
            $table->string('crsl_subtitle')->nullable();
            $table->string('crsl_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('front_carousel');
    }
}
