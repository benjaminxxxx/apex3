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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users'); // ID del usuario que envía el mensaje
            $table->foreignId('recipient_id')->constrained('users'); // ID del usuario destinatario del mensaje
            $table->text('content'); // Contenido del mensaje
            $table->string('status')->default('unread'); // Estado del mensaje ('unread' o 'read')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
