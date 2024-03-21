<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->index('posted_date');
        });

        Schema::table('periods', function (Blueprint $table) {
            $table->index(['start_date', 'end_date']);
        });

        Schema::table('crawler_log', function (Blueprint $table) {
            $table->index('status');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['posted_date']);
        });

        Schema::table('periods', function (Blueprint $table) {
            $table->dropIndex(['start_date', 'end_date']);
        });

        Schema::table('crawler_log', function (Blueprint $table) {
            $table->dropIndex('status');
        });
    }
}
