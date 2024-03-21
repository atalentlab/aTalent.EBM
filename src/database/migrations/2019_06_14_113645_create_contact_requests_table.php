<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_requests');
    }
}
