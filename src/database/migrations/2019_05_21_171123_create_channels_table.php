<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->boolean('published')->default(0);
            $table->json('name');
            $table->integer('order')->default(0);
            $table->string('logo')->nullable();
            $table->string('organization_url_prefix')->nullable();
            $table->string('organization_url_suffix')->nullable();
            $table->integer('ranking_weight')->default(0);
            $table->integer('weight_activity')->default(0);
            $table->integer('weight_popularity')->default(0);
            $table->integer('weight_engagement')->default(0);
            $table->integer('post_max_fetch_age')->default(0);

            $table->boolean('can_collect_views_data')->default(0);
            $table->boolean('can_collect_likes_data')->default(0);
            $table->boolean('can_collect_comments_data')->default(0);
            $table->boolean('can_collect_shares_data')->default(0);

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
        Schema::dropIfExists('channels');
    }
}
