<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelContactRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_contact_request', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('channel_id')->unsigned()->nullable();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');

            $table->bigInteger('contact_request_id')->unsigned()->nullable();
            $table->foreign('contact_request_id')->references('id')->on('contact_requests')->onDelete('cascade');
            $table->string('channel_username');

            $table->unique(['channel_id', 'contact_request_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_contact_request');
    }
}
