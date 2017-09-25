<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_master', function (Blueprint $table) {
            $table->bigIncrements('file_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('thumbnail_id')->nullable();
            $table->string('type', 15);
            $table->string('name', 500);
            $table->string('path', 500);
            $table->string('extension', 15);
            $table->dateTime('created_at')->default(Carbon\Carbon::now()->format('Y-m-d H:i:s'));
            $table->unsignedBigInteger('upload_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files_master');
    }
}
