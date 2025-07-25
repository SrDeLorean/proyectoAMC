<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadisticaJugadoresTable extends Migration
{
    public function up(): void
    {
        Schema::create('estadistica_jugadores', function (Blueprint $table) {
            $table->id();

            // Relaciones foráneas
            $table->foreignId('id_jugador')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_equipo')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('id_calendario')->constrained('calendarios')->onDelete('cascade');
            $table->foreignId('id_temporada_competencia')->constrained('temporada_competencias')->onDelete('cascade');

            // Datos del jugador
            $table->string('posicion')->nullable();
            $table->string('nombre'); // Si no se toma directamente del modelo User

            // Estadísticas
            $table->float('calificacion')->nullable();
            $table->unsignedTinyInteger('goles')->nullable();
            $table->unsignedTinyInteger('asistencias')->nullable();
            $table->unsignedTinyInteger('tiros')->nullable();
            $table->unsignedTinyInteger('precision_tiros')->nullable(); // %
            $table->unsignedSmallInteger('pases')->nullable();
            $table->unsignedTinyInteger('precision_pases')->nullable(); // %
            $table->unsignedTinyInteger('regates')->nullable();
            $table->unsignedTinyInteger('tasa_exito_entradas')->nullable(); // %

            $table->unsignedTinyInteger('fueras_de_juego')->nullable();
            $table->unsignedTinyInteger('faltas_cometidas')->nullable();
            $table->unsignedTinyInteger('posesion_ganada')->nullable();
            $table->unsignedTinyInteger('posesion_perdida')->nullable();

            $table->unsignedTinyInteger('minutos_jugados_vs_equipo')->nullable(); // %
            $table->float('distancia_total_vs_equipo')->nullable(); // km
            $table->float('distancia_carrera_vs_equipo')->nullable(); // km

            $table->boolean('procesado')->default(false);
            $table->string('foto')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadistica_jugadores');
    }
}
