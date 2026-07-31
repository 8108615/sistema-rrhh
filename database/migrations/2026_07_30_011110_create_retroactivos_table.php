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
        Schema::create('retroactivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            // Gestión o año al que corresponde el retroactivo (ej. 2026)
            $table->year('gestion');
            // Porcentaje de incremento salarial aplicado
            $table->decimal('porcentaje', 5, 2)->default(0);
            // Sueldos de referencia
            $table->decimal('sueldo_anterior', 10, 2);
            $table->decimal('sueldo_nuevo', 10, 2);
            // Diferencia mensual calculada (Sueldo Nuevo - Sueldo Anterior)
            $table->decimal('diferencia_mensual', 10, 2);
            // Cantidad de meses que abarca el retroactivo (ej. 1 a 12 meses)
            $table->integer('meses_aplicados')->default(1);
            // Monto Total Final a Pagar (Diferencia Mensual * Meses Aplicados)
            $table->decimal('monto_pagar', 10, 2);
            // Estado y fechas de control
            $table->enum('estado', ['Pendiente', 'Pagado'])->default('Pendiente');
            $table->date('fecha_pago')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retroactivos');
    }
};
