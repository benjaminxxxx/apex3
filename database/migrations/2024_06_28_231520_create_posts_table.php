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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->char('code',15);
            $table->string('title');
            $table->text('content');
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->boolean('allow_comments')->default(1); 
            $table->text('excerpt')->nullable(); 
            $table->enum('status', ['1', '0','2'])->default('1');
            $table->tinyInteger('type')->default(1); //noticias generales //noticias en proyetctos //noticias en grupos
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
