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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->char('code',15);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->unsignedBigInteger('created_by');
            $table->boolean('status')->default(true);
            $table->unsignedTinyInteger('type'); // 1: Global, 2: Project, 3: Group
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('user_to')->nullable();

            // Añadir índices para mejorar el rendimiento de las consultas
            $table->index(['project_id']);
            $table->index(['group_id']);
            $table->index(['user_to']);

            $table->timestamps();
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            $table->foreign('user_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('document_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedTinyInteger('role_id'); // 2: Administradores, 3: Gestores, 4: Socios

            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_roles');
        Schema::dropIfExists('documents');
    }
};
