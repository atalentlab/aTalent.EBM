<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NameFieldToJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->json('name_new')->nullable()->after('name');
        });

        Schema::table('industries', function (Blueprint $table) {
            $table->json('name_new')->nullable()->after('name');
        });

        Schema::table('periods', function (Blueprint $table) {
            $table->json('name_new')->nullable()->after('name');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->json('name_new')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('name_new');
        });

        Schema::table('industries', function (Blueprint $table) {
            $table->dropColumn('name_new');
        });

        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('name_new');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('name_new');
        });
    }
}
