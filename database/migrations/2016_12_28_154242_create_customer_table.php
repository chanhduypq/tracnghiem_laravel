<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->bigIncrements('customer_id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('customer_name', 255)->nullable();
            $table->date('foundation_date')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('fax', 15)->nullable();
            $table->text('description')->nullable();
            $table->longText('detail')->nullable();
            $table->string('video')->nullable();
            $table->string('image')->nullable();
            $table->string('country', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('street_number', 255)->nullable();
            $table->string('street_name', 255)->nullable();
            $table->string('postal_code', 12)->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'customer_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
