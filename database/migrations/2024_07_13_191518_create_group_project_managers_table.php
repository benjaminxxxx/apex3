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
        Schema::create('group_project_manager', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id'); // Foreign key referencing groups
            $table->unsignedBigInteger('project_id'); // Foreign key referencing projects
            $table->unsignedBigInteger('manager_id'); // Foreign key referencing user with role_id = 2 (manager)
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_project_manager');
    }
};
