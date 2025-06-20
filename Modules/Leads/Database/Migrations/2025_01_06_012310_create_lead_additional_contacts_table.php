<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lead_additional_contacts', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('lead_id')->unsigned();
            $table->foreign('lead_id')->references('id')->on('leads')
                ->onDelete('cascade');

            $table->string('phone', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('location', 70)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_additional_contacts');
    }
};
