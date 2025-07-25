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
            $table->foreignId('equipo_local_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('equipo_visitante_id')->constrained('equipos')->onDelete('cascade');
            $table->integer('goles_equipo_local')->nullable();
            $table->integer('goles_equipo_visitante')->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();
            $table->string('jornada')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendarios');
    }
}
