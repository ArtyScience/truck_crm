<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Task\Entities\TaskModel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->bigInteger('deal_id')->unsigned()->nullable();
            $table->foreign('deal_id')->references('id')
                ->on('deals')->onDelete('cascade');

            $table->bigInteger('lead_id')->unsigned()->nullable();
            $table->foreign('lead_id')->references('id')
                ->on('leads')->onDelete('cascade');

            $table->tinyInteger('status')->default(TaskModel::ACTIVE);

            $table->dateTime('deadline')->nullable();

            $table->tinyInteger('priority')->default(TaskModel::HIGHT_PRIORITY);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
