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
        Schema::create('charts', function (Blueprint $table) {
            $table->id();
            $table->char('chart_id', 10)->unique();
            $table->text('data');
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->integer('height');
            $table->text('title');
            $table->enum('order_by', ['columns', 'rows']);
            $table->enum('showlabels', ['0', '1']);
            $table->enum('showlegend', ['0', '1']);
            $table->enum('status', ['1', '0','2'])->default('1');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charts');
    }
};