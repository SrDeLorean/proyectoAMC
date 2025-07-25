<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('traspasos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_jugador')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_equipo_origen')->nullable()->constrained('equipos')->onDelete('set null');
            $table->foreignId('id_equipo_destino')->constrained('equipos')->onDelete('cascade');

            $table->text('motivo')->nullable();

            $table->enum('estado', ['pendiente', 'aceptado', 'rechazado', 'aprobado', 'cancelado'])->default('pendiente');

            $table->timestamp('fecha_traspaso')->useCurrent();
            $table->timestamp('fecha_respuesta_jugador')->nullable();
            $table->timestamp('fecha_revision_admin')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traspasos');
    }
};
