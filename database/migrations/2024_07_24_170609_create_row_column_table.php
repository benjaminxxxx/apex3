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
        Schema::create('row_column', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('row_id');
            $table->unsignedBigInteger('column_id');
            $table->string('data');
            $table->timestamps();

            $table->foreign('row_id')->references('id')->on('row_charts')->onDelete('cascade');
            $table->foreign('column_id')->references('id')->on('column_charts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('row_column');
    }
};
