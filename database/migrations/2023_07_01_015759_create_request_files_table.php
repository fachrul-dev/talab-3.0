<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id')->unsigned();

            $table->integer('files_id')->unsigned();

            $table->foreign('request_id')->references('id')->on('request_data')

                ->onDelete('cascade');

            $table->foreign('files_id')->references('id')->on('files')

                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_files');
    }
};
