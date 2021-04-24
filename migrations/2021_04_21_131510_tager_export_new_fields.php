<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TagerExportNewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tager_export_sessions', function (Blueprint $table) {
            $table->string('filename')->after('strategy');
            $table->string('format')->after('strategy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tager_export_sessions', function (Blueprint $table) {
            $table->dropColumn('format');
            $table->dropColumn('filename');
        });
    }
}
