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
        Schema::create('status_request', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', ['pending','processed','approved', 'rejected']);
            $table->string('struktur');
            $table->dateTime('ChangedAt')->nullable();
            $table->string('ChangedByEmail')->nullable();
            $table->integer('request_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_request');
    }
};
