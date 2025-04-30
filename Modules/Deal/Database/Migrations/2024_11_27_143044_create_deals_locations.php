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
        Schema::create('deal_locations', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('deal_id')->unsigned();
            $table->foreign('deal_id')->references('id')
                ->on('deals')->onDelete('cascade');

            //info: default (from/to)
            $table->char('type', 4)->index();

            $table->string('full_location');
            $table->string('country');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country_code');
            $table->string('postcode');
            $table->string('latitude');
            $table->string('longitude');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_locations');
    }
};
