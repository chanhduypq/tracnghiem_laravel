<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('news_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('category');
            $table->string('title', 500);
            $table->text('description');
            $table->longText('content');
            $table->string('image', 500)->nullable();
            $table->string('video', 500)->nullable();
            $table->boolean('draft')->nullable();
            $table->boolean('active')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
            $table->index(['news_id', 'user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
