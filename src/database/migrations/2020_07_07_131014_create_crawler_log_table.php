<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrawlerLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawler_log', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('organization_id')->unsigned()->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->bigInteger('period_id')->unsigned()->nullable();
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');

            $table->bigInteger('channel_id')->unsigned()->nullable();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');

            $table->bigInteger('api_user_id')->unsigned()->nullable();
            $table->foreign('api_user_id')->references('id')->on('api_users')->onDelete('cascade');

            $table->string('status')->default('success');
            $table->integer('posts_crawled_count')->default(0);
            $table->boolean('is_organization_data_sent')->default(0);
            $table->text('message')->nullable();
            $table->string('crawler_ip')->nullable();
            $table->integer('crawled_count')->default(1);

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
        Schema::dropIfExists('crawler_log');
    }
}
