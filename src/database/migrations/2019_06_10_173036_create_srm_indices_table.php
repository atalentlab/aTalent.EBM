<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSrmIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('srm_indices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('organization_id')->unsigned()->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->bigInteger('period_id')->unsigned()->nullable();
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');

            $table->decimal('composite', 10, 2)->default(0);
            $table->decimal('composite_shift', 10, 2)->default(0);
            $table->decimal('activity', 10, 2)->default(0);
            $table->decimal('engagement', 10, 2)->default(0);
            $table->decimal('popularity', 10, 2)->default(0);

            $table->unique(['organization_id', 'period_id']);

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
        Schema::dropIfExists('srm_indices');
    }
}
