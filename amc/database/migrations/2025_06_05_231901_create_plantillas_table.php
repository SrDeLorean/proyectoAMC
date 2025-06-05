<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantillasTable extends Migration
{
    public function up()
    {
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_equipo')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('id_jugador')->constrained('users')->onDelete('cascade');
            $table->string('posicion');
            $table->integer('numero');
            $table->timestamps();
            $table->softDeletes(); // para soft delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('plantillas');
    }

};
