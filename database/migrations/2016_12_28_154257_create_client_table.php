<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client', function (Blueprint $table) {
            $table->bigIncrements('client_id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('avatar')->nullable();
            $table->string('country', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('street_number', 255)->nullable();
            $table->string('street_name', 255)->nullable();
            $table->string('postal_code', 12)->nullable();
            $table->string('phone', 15)->nullable();
            $table->boolean('sex')->nullable();
            $table->text('description')->nullable();
            $table->date('birthday')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'client_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client');
    }
}
