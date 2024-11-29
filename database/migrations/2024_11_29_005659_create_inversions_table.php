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
        Schema::create('inversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inversor_id')->constrained('users')->cascadeOnDelete(); // Relación con users
            $table->foreignId('projecto_id')->constrained('projects')->cascadeOnDelete(); // Relación con projects
            $table->foreignId('grupo_id')->constrained('groups')->cascadeOnDelete(); // Relación con groups
            $table->decimal('capital_invertido', 15, 2);
            $table->decimal('tasa_interes_anual', 5, 2);
            $table->enum('tipo_interes', ['simple', 'compuesto']);
            $table->string('frecuencia_pago', 50); // Ej: mensual, trimestral, anual
            $table->date('fecha_inicio');
            $table->integer('plazo'); // Plazo en meses
            $table->date('fecha_vencimiento');
            $table->decimal('capital_final', 15, 2)->nullable(); // Calculado al cierre de la inversión
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inversions');
    }
};
