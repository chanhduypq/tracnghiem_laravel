<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToCustomerTable extends Migration
{

    public function up()
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->string('website', 500)->nullable();
        });
    }

    public function down()
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->dropColumn('website');
        });
    }
}
