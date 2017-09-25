<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageDataTable extends Migration
{

    public function up()
    {
        Schema::create('message', function (Blueprint $table) {
            $table->bigIncrements('message_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('received_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('message_type')->nullable();
            $table->string('subject', 500);
            $table->text('content');
            $table->timestamp('sender_deleted_at')->nullable();
            $table->timestamp('received_deleted_at')->nullable();
            $table->softDeletes();
            $table->timestamps();;
        });
    }


    public function down()
    {
        Schema::dropIfExists('message');
    }
}
