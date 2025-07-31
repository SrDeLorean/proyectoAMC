<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadisticaEquiposTable extends Migration
{
    public function up(): void
    {
        Schema::create('estadistica_equipos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_equipo')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('id_calendario')->constrained('calendarios')->onDelete('cascade');
            $table->foreignId('id_temporadacompetencia')->constrained('temporada_competencias')->onDelete('cascade');

            // Estadísticas del equipo
            $table->unsignedTinyInteger('posesion')->nullable();                    // Porcentaje de posesión
            $table->unsignedTinyInteger('tasa_exito_regates')->nullable();         // Porcentaje de regates exitosos
            $table->unsignedTinyInteger('precision_tiros')->nullable();            // Porcentaje de precisión de tiros
            $table->unsignedTinyInteger('precision_pases')->nullable();            // Porcentaje de precisión de pases

            $table->unsignedTinyInteger('recuperacion_balon')->nullable();         // Recuperación de balón en segundos
            $table->unsignedTinyInteger('tiros')->nullable();                      // Total tiros
            $table->float('goles_esperados')->nullable();                          // Goles esperados (xG)
            $table->unsignedSmallInteger('pases')->nullable();                     // Total pases
            $table->unsignedSmallInteger('entradas')->nullable();                  // Entradas realizadas
            $table->unsignedSmallInteger('entradas_con_exito')->nullable();        // Entradas exitosas
            $table->unsignedSmallInteger('recuperaciones')->nullable();            // Recuperaciones
            $table->unsignedTinyInteger('atajadas')->nullable();                   // Atajadas del portero
            $table->unsignedTinyInteger('faltas_cometidas')->nullable();           // Faltas cometidas
            $table->unsignedTinyInteger('fuera_de_juego')->nullable();             // Posiciones de fuera de juego
            $table->unsignedTinyInteger('tiros_de_esquina')->nullable();           // Tiros de esquina
            $table->unsignedTinyInteger('tiros_libres')->nullable();               // Tiros libres
            $table->unsignedTinyInteger('penales')->nullable();                    // Penales
            $table->unsignedTinyInteger('tarjetas_amarillas')->nullable();         // Tarjetas amarillas

            $table->boolean('procesado')->default(false);                          // Indica si la estadística ha sido procesada
            $table->string('foto')->nullable();

            $table->timestamps();
            $table->softDeletes(); // Para borrado lógico
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadistica_equipos');
    }
}
