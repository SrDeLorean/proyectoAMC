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
            $table->foreignId('id_equipo')->constrained('equipos')->onDelete('cascade'); // Asegúrate de tener tabla `equipos`
            $table->timestamps();
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
