<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNewsTables extends Migration
{
    public function up()
    {
//        For now, the requirement is not clear, So, I guess when user click on button to get Catalog,
//        then system auto go to link catalog or send email to customer.
//        After that, the system will automatic write logs
        Schema::table('news', function (Blueprint $table) {
            $table->string('link_catalog', 255)->nullable();
            $table->string('email_customer', 255)->nullable();
        });
    }

}
