<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // NOTA: Eliminamos la creación de 'sessions' para evitar duplicados.

        // Crear tabla de jobs para queue
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
            $table->index(['queue', 'reserved_at']);
        });

        // Crear tabla de job batches
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->text('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
            $table->index(['created_at']);
        });

        // Crear tabla de failed jobs
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
            $table->index(['uuid', 'failed_at']);
        });

        // Crear tabla de caché
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->unique();
            $table->mediumText('value');
            $table->integer('expiration')->index();
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->unique();
            $table->string('owner');
            $table->integer('expiration')->index();
        });

        // Crear índices en tablas usuarios
        DB::statement('CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_users_created_at ON users(created_at)');
        
        // --- AQUÍ ESTABA EL ERROR ---
        // Actualizamos la vista para comparar con TEXTO ('completada') en lugar de NÚMERO (3)
        DB::statement(
            "CREATE OR REPLACE VIEW v_tareas_por_usuario AS
             SELECT 
                u.id,
                u.name,
                COUNT(t.id) as total_tareas,
                SUM(CASE WHEN t.estado = 'completada' THEN 1 ELSE 0 END) as completadas,
                SUM(CASE WHEN t.estado != 'completada' THEN 1 ELSE 0 END) as pendientes
             FROM users u
             LEFT JOIN tareas t ON u.id = t.user_id AND t.deleted_at IS NULL
             GROUP BY u.id, u.name"
        );

        // Vista de balance (también corregida para usar texto 'ingreso')
        DB::statement(
            "CREATE OR REPLACE VIEW v_balance_por_usuario AS
             SELECT 
                u.id,
                u.name,
                COALESCE(SUM(CASE WHEN tr.tipo = 'ingreso' THEN tr.monto ELSE -tr.monto END), 0) as balance_total,
                COUNT(DISTINCT tr.id) as total_transacciones
             FROM users u
             LEFT JOIN transacciones tr ON u.id = tr.user_id AND tr.deleted_at IS NULL
             GROUP BY u.id, u.name"
        );

        // Habilitar extensiones útiles
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        DB::statement('CREATE EXTENSION IF NOT EXISTS "pg_stat_statements"');
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        
        DB::statement('DROP VIEW IF EXISTS v_tareas_por_usuario');
        DB::statement('DROP VIEW IF EXISTS v_balance_por_usuario');
    }
};