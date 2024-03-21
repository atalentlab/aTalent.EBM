<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_organization', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('channel_id')->unsigned()->nullable();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');

            $table->bigInteger('organization_id')->unsigned()->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->string('channel_username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_organization');
    }
}
