<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('habito_registros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habito_id')->constrained()->cascadeOnDelete();
            $table->date('fecha');
            $table->boolean('completado')->default(false);
            $table->timestamps();
            $table->unique(['habito_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habito_registros');
    }
};
