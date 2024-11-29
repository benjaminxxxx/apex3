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
        Schema::table('groups', function (Blueprint $table) {
            $table->foreignId('administrator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete(); // Deja el campo en null si el usuario se elimina
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign(['administrator_id']);
            $table->dropColumn('administrator_id');
        });
    }
};
