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
        Schema::create('ringcentral_calls', function (Blueprint $table) {
            $table->id();
            $table->string('file_path')->nullable();
            $table->string('record_id')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ringcentral_calls');
    }
};
