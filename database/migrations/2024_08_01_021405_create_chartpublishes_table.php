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
        Schema::create('chartpublishes', function (Blueprint $table) {
            $table->id();
            $table->char('code', 10)->unique();
            $table->text('data');
            $table->text('description')->nullable();
            $table->tinyInteger('chart_type')->default(1);
            $table->string('type');
            $table->text('title');
            $table->enum('showlabels', ['0', '1']);
            $table->enum('showlegend', ['0', '1']);
            $table->unsignedBigInteger('chart_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->timestamps();
            $table->foreign('chart_id')->references('id')->on('charts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chartpublishes');
    }
};
