<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TagerExportInit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tager_export_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('strategy');
            $table->text('params')->nullable();
            $table->text('message')->nullable();
            $table->unsignedBigInteger('file_id')->nullable();
            $table->string('file_name')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->foreign('file_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_sessions');
    }
}
