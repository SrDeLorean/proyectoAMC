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
        Schema::create('temporada_plantillas', function (Blueprint $table) {
            $table->id();

            // Relación con temporada_equipos (un equipo participando en una temporada)
            $table->foreignId('id_temporada_equipo')->constrained('temporada_equipos')->onDelete('cascade');

            // Relación con users (jugadores)
            $table->foreignId('id_jugador')->constrained('users')->onDelete('cascade');

            // Número de camiseta y posición específica para esa temporada
            $table->string('rol');
            $table->string('posicion');
            $table->unsignedTinyInteger('numero');

            $table->timestamps();
            $table->softDeletes(); // para borrado lógico
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporada_plantillas');
    }
};
