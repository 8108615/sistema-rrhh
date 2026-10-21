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
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            $table->string('origen')->default('Santa Cruz'); // Ciudad/Departamento de origen
            $table->string('destino');                     // Ciudad/Departamento de destino
            $table->string('estadio')->nullable();          // Estadio o recinto deportivo/evento
            $table->text('motivo')->nullable();            // Descripción/Partido
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('dias_totales');
            $table->decimal('monto_diario', 10, 2);
            $table->decimal('total_por_persona', 10, 2);
            $table->decimal('monto_total_viaje', 10, 2);
            $table->string('estado')->default('PENDIENTE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
