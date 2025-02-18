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
        Schema::create('deals_options', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('deal_id')->unsigned();
            $table->foreign('deal_id')
                ->references('id')
                ->on('deals')->onDelete('cascade');

            $table->boolean('pick_up')->default(false);
            $table->boolean('delivery')->default(false);
            $table->boolean('haz')->default(false);
            $table->boolean('tarp')->default(false);
            $table->boolean('temp')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals_options');
    }
};
