<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('habitos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('nombre', 255)->index();
            $table->text('descripcion')->nullable();
            $table->smallInteger('racha')->default(0)->index();
            $table->integer('puntos')->default(0);
            $table->boolean('activo')->default(true)->index();
            $table->string('frecuencia', 20)->default('diaria'); // diaria, semanal, mensual
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable()->index();
            
            // Constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Composite indexes
            $table->index(['user_id', 'activo'], 'idx_user_habito_activo');
            $table->index(['user_id', 'racha'], 'idx_user_racha');
        });
        
        DB::statement("COMMENT ON TABLE habitos IS 'Almacena los hábitos de los usuarios con seguimiento de racha y puntos'");
    }

    public function down(): void
    {
        Schema::dropIfExists('habitos');
    }
};
