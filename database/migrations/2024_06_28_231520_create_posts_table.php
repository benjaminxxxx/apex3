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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->boolean('allow_comments')->default(1); 
            $table->text('excerpt')->nullable(); 
            $table->enum('status', ['1', '0'])->default('1');
            $table->enum('type', ['noticia', 'evento', 'publicacion', 'foro'])->default('noticia');
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
