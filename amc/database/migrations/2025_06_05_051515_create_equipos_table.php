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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('color_primario')->nullable();
            $table->string('color_secundario')->nullable();
            $table->string('logo')->nullable();
            $table->foreignId('id_formacion')->nullable()->constrained('formaciones')->nullOnDelete();
            $table->string('instagram')->nullable();
            $table->string('twitch')->nullable();
            $table->string('youtube')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->unsignedBigInteger('id_usuario2')->nullable();

            $table->foreign('id_usuario', 'equipos_id_usuario_foreign')
                ->references('id')->on('users')->onDelete('cascade');

            $table->foreign('id_usuario2', 'equipos_id_usuario2_foreign')
                ->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
