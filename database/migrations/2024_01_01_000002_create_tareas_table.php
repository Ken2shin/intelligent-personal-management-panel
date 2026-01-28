<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('titulo', 255)->index();
            $table->text('descripcion')->nullable();
            
            // CORRECCIÓN: Cambiamos de smallInteger a string para aceptar texto
            $table->string('prioridad', 20)->default('media'); // Ej: baja, media, alta
            $table->string('estado', 20)->default('pendiente'); // Ej: pendiente, en_progreso
            
            $table->dateTime('fecha_limite')->nullable()->index();
            
            // Timestamps estándar
            $table->timestamps();
            $table->softDeletes(); // Agrega deleted_at
            
            // Constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Composite indexes for optimal query performance
            $table->index(['user_id', 'estado', 'fecha_limite'], 'idx_user_estado_fecha');
            $table->index(['user_id', 'estado'], 'idx_user_estado');
            $table->index(['user_id', 'created_at'], 'idx_user_created');
        });
        
        // Add comment for documentation
        DB::statement("COMMENT ON TABLE tareas IS 'Almacena las tareas de los usuarios con prioridades y estados'");
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};