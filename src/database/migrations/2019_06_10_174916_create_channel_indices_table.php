<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_indices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('srm_index_id')->unsigned()->nullable();
            $table->foreign('srm_index_id')->references('id')->on('srm_indices')->onDelete('cascade');

            $table->bigInteger('channel_id')->unsigned()->nullable();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');

            $table->bigInteger('organization_id')->unsigned()->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->bigInteger('period_id')->unsigned()->nullable();
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');

            $table->decimal('composite', 10, 2)->default(0);
            $table->decimal('composite_shift', 10, 2)->default(0);
            $table->decimal('activity', 10, 2)->default(0);
            $table->decimal('engagement', 10, 2)->default(0);
            $table->decimal('popularity', 10, 2)->default(0);

            $table->integer('follower_count')->nullable();
            $table->integer('post_count')->nullable();
            $table->integer('like_count')->nullable();
            $table->integer('comment_count')->nullable();
            $table->integer('share_count')->nullable();
            $table->integer('view_count')->nullable();

            $table->unique(['channel_id', 'organization_id', 'period_id']);

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
        Schema::dropIfExists('channel_indices');
    }
}
