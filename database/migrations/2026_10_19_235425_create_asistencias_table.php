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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->date('fecha');
            $table->time('primera_entrada')->nullable();
            $table->time('ultima_salida')->nullable();
            $table->decimal('horas_trabajadas', 5, 2)->default(0); // Total neto calculado
            $table->enum('estado', ['PRESENTE', 'MEDIO_TIEMPO', 'FALTA', 'PERMISO'])->default('PRESENTE');
            $table->timestamps();

            // Evitamos duplicar el resumen de un empleado el mismo día
            $table->unique(['empleado_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
