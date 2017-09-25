<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNewsTable2 extends Migration
{
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('target_page', 255)->nullable()->change();
        });
    }
}
