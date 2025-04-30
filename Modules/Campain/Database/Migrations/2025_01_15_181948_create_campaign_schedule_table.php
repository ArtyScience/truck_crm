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
        Schema::create('campaign_schedule', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('campaign_id')->unsigned();
            $table->foreign('campaign_id')->references('id')
                ->on('campaigns')->onDelete('cascade');

            $table->string('timezone', 75)->default("Europe/Chisinau");

            $table->json('days_of_the_week');

            $table->time('start_hour');
            $table->time('end_hour');
            $table->tinyInteger('min_time_btw_emails')->default(1);
            $table->integer('max_new_leads_per_day')->default(3000);
            $table->dateTime('schedule_start_time');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_schedule');
    }
};
