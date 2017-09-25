<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{

    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->bigIncrements('like_id');
            $table->unsignedBigInteger('user_like_id');
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id');
            $table->integer('value');
            $table->softDeletes();
            $table->timestamps();;
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
