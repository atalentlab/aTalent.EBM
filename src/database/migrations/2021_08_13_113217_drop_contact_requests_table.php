<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropContactRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('channel_contact_request');
        Schema::dropIfExists('contact_requests');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
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

        Schema::create('contact_requests', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->boolean('is_sent_to_edm_provider')->default(0);
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->enum('status', ['to_review', 'in_progress', 'rejected', 'accepted'])->default('to_review');

            $table->bigInteger('organization_id')->unsigned()->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');

            $table->timestamps();
        });
    }
}
