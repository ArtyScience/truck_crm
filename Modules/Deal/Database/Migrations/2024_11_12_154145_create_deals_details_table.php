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
        Schema::create('deals_details', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('deal_id')->unsigned();
            $table->foreign('deal_id')
                ->references('id')
                ->on('deals')->onDelete('cascade');

            $table->date('pick_up_date');
            $table->date('delivery_date');

            $table->string('equipment_type', 100);
            $table->string('shipment_type', 100);

            $table->string('deal_type', 45);
            $table->text('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals_details');
    }
};
