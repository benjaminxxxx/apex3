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
        Schema::create('column_charts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('chart_id');
            $table->foreign('chart_id')->references('id')->on('charts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('column_charts');
    }
};
