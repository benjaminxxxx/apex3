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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs'); // Eliminar la tabla 'jobs'
        Schema::dropIfExists('job_batches'); // Eliminar la tabla 'job_batches'
        Schema::dropIfExists('failed_jobs'); // Eliminar la tabla 'failed_jobs'
    
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nickname'); // Eliminar la columna 'nickname' de la tabla 'users'
            $table->dropColumn('lastname'); // Eliminar la columna 'lastname' de la tabla 'users'
            $table->dropForeign(['role_id']); // Eliminar la clave foránea de 'role_id' en la tabla 'users'
            $table->dropColumn('role_id'); // Eliminar la columna 'role_id' de la tabla 'users'
        });
    }
};
