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
        Schema::create('finiquitos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            // Datos del proceso de desvinculación
            $table->date('fecha_ingreso');
            $table->date('fecha_retiro');
            $table->string('causal_retiro'); // Ej: Renuncia Voluntaria, Despido Injustificado, Abandono de Trabajo, etc.

            // Bases salariales (según ley boliviana, se usa el último o el promedio de los últimos 3 meses)
            $table->decimal('ultimo_salario', 10, 2);
            $table->decimal('promedio_tres_salarios', 10, 2);

            // Tiempo de servicios calculado (para constancia o impresión)
            $table->decimal('anos_servicio', 8, 2)->default(0);

            // Montos desglosados de beneficios sociales
            $table->decimal('monto_indemnizacion', 10, 2)->default(0); // Tiempo de servicios
            $table->decimal('monto_desahucio', 10, 2)->default(0);      // 3 meses (solo si es despido injustificado)
            $table->decimal('monto_vacacion', 10, 2)->default(0);     // Vacaciones no gozadas / proporcionales
            $table->decimal('monto_aguinaldo', 10, 2)->default(0);    // Aguinaldo proporcional de la gestión

            // Total general a pagar
            $table->decimal('total_beneficios', 10, 2)->default(0);

            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finiquitos');
    }
};
