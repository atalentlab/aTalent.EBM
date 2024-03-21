<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_data', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('organization_id')->unsigned()->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->bigInteger('period_id')->unsigned()->nullable();
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');

            $table->bigInteger('channel_id')->unsigned()->nullable();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');

            $table->integer('follower_count')->default(0);

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
        Schema::dropIfExists('organization_data');
    }
}
