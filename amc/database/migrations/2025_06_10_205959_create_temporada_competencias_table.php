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
        Schema::create('temporada_competencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('id_temporada')->constrained('temporadas')->onDelete('cascade');
            $table->foreignId('id_competencia')->constrained('competencias')->onDelete('cascade');
            $table->string('formato')->nullable(); // 'liga' o 'copa'
            $table->date('fecha_inicio');
            $table->date('fecha_termino')->nullable();

            // Campos para controlar fichajes
            $table->boolean('fichajes_abiertos')->default(true);
            $table->timestamp('fichajes_inicio')->nullable();
            $table->timestamp('fichajes_fin')->nullable();

            $table->timestamps();
            $table->softDeletes(); // para soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporada_competencias');
    }
};
