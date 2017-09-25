<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYoutubeTable extends Migration
{
    public function up()
    {
        Schema::create('youtube', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('target_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('youtube_id', 55);
            $table->string('target_type');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('youtube');
    }
}
