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
        Schema::create('temporada_equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_temporadacompetencia')->constrained('temporada_competencias')->onDelete('cascade');
            $table->foreignId('id_equipo')->constrained('equipos')->onDelete('cascade');

            // Campos estadísticos
            $table->unsignedTinyInteger('puntos')->default(0);       // P
            $table->unsignedTinyInteger('partidos_jugados')->default(0); // M
            $table->unsignedTinyInteger('victorias')->default(0);    // V
            $table->unsignedTinyInteger('empates')->default(0);      // D
            $table->unsignedTinyInteger('derrotas')->default(0);     // L
            $table->unsignedTinyInteger('goles_a_favor')->default(0); // GP
            $table->unsignedTinyInteger('goles_en_contra')->default(0); // GC
            $table->integer('diferencia_goles')->default(0);         // SG = GP - GC

            $table->timestamps();
            $table->softDeletes(); // para borrado lógico
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporada_equipos');
    }
};
