<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('comment_id');
            $table->unsignedBigInteger('user_comment_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id');
            $table->text('content');
            $table->softDeletes();
            $table->timestamps();;
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
