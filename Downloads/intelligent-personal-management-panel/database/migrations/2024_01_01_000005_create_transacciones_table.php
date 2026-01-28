<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            
            // CORRECCIÓN IMPORTANTE:
            // Cambiamos de 'smallInteger' a 'string' para que acepte "ingreso" y "gasto".
            $table->string('tipo', 20)->default('gasto'); 
            
            $table->string('categoria', 100)->index();
            
            // CORRECCIÓN: Usamos 'decimal' que es el estándar de Laravel
            $table->decimal('monto', 14, 2); 
            
            $table->date('fecha')->index();
            $table->text('descripcion')->nullable();
            $table->string('metodo_pago', 50)->nullable()->index(); 
            $table->string('referencia', 100)->nullable()->unique();
            
            // Timestamps estándar
            $table->timestamps(); // Esto crea created_at y updated_at automáticamente
            $table->softDeletes(); // Esto crea deleted_at
            
            // Constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Índices compuestos para consultas financieras
            $table->index(['user_id', 'fecha'], 'idx_user_fecha');
            $table->index(['user_id', 'tipo', 'fecha'], 'idx_user_tipo_fecha');
            $table->index(['user_id', 'categoria'], 'idx_user_categoria');
        });
        
        // Comentario opcional para PostgreSQL
        DB::statement("COMMENT ON TABLE transacciones IS 'Almacena movimientos financieros (ingresos y gastos)'");
    }

    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};