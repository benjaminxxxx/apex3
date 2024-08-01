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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->char('code',15);
            $table->string('title');
            $table->string('slug')->unique();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('organizer');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->text('map')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('content');
            $table->tinyInteger('type')->default(1); // 1: Global, 2: Project
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Referencia al usuario que creó el evento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
