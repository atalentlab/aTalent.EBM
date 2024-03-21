<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('industry_id')->unsigned()->nullable();
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('set null');

            $table->boolean('published')->default(0);
            $table->boolean('is_fetching')->default(0);

            $table->json('name');
            $table->text('intro')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_title')->nullable();

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
        Schema::dropIfExists('organizations');
    }
}
