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
        Schema::create('request_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('requirements');
            $table->enum('type', ['fte','fte_director', 'nonfte', 'nonfte_contract']);
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('request_data', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_data');
    }
};
