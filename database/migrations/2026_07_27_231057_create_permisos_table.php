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
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->string('tipo'); // Vacaciones, Permiso Personal, Baja Médica
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->date('fecha_retorno')->nullable(); // Nuevo campo
            $table->integer('dias_solicitados');
            $table->text('motivo')->nullable();
            $table->enum('estado', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');

            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
