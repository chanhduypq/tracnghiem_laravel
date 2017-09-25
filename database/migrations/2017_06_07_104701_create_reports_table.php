<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('report_id');
            $table->unsignedBigInteger('user_report_id');
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id');
            $table->integer('value');
            $table->text('content')->nullable();
            $table->softDeletes();
            $table->timestamps();;
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
