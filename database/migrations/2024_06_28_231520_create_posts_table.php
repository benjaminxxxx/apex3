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
            $table->enum('status', ['1', '0','2'])->default('1');
            $table->enum('type', ['noticia', 'evento', 'publicacion', 'foro'])->default('noticia');

            // Campos adicionales para eventos
            $table->dateTime('starts_at')->nullable(); // Fecha y hora de inicio del evento
            $table->dateTime('ends_at')->nullable();   // Fecha y hora de finalización del evento
            $table->string('organizer')->nullable();   // Organizador del evento
            $table->string('phone')->nullable();       // Teléfono de contacto
            $table->string('email')->nullable();       // Correo de contacto
            $table->string('location')->nullable();    // Local del evento
            $table->string('website')->nullable();     // Página web del evento
            $table->string('map')->nullable();         // URL del mapa del evento
            
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
