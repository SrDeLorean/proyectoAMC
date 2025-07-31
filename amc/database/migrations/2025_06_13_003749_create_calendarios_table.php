<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendariosTable extends Migration
{
    public function up()
    {
        Schema::create('calendarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_temporadacompetencia')->constrained('temporada_competencias')->onDelete('cascade');
            $table->foreignId('id_equipo_local')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('id_equipo_visitante')->constrained('equipos')->onDelete('cascade');
            $table->integer('goles_equipo_local')->nullable();
            $table->integer('goles_equipo_visitante')->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();
            $table->string('jornada')->nullable();

            //se vana subir 3 fotos, rendimiendo del partido, lista de ID del partido, rendimiento de los jugadores
            $table->string('foto_rendimiento_local')->nullable();
            $table->string('foto_lista_id_local')->nullable();
            $table->string('foto_rendimiento_jugadores_local')->nullable();

            //se vana subir 3 fotos, rendimiendo del partido, lista de ID del partido, rendimiento de los jugadores
            $table->string('foto_rendimiento_visitante')->nullable();
            $table->string('foto_lista_id_visitante')->nullable();
            $table->string('foto_rendimiento_jugadores_visitante')->nullable();
            $table->timestamps();
            $table->softDeletes(); // para borrado lógico
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendarios');
    }
}
