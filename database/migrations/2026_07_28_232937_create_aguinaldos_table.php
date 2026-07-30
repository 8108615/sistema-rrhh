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
        Schema::create('aguinaldos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->year('gestion'); // Año (ej. 2026)

            // Tipo de beneficio o pago especial
            $table->enum('tipo', ['Aguinaldo', 'Doble Aguinaldo'])->default('Aguinaldo');

            // Bases salariales exigidas por ley
            $table->decimal('ultimo_salario', 10, 2);
            $table->decimal('promedio_tres_meses', 10, 2);
            $table->decimal('base_calculo', 10, 2);

            // Tiempo trabajado en la gestión (proporcionales)
            $table->integer('meses_trabajados')->default(0);
            $table->integer('dias_trabajados')->default(0);

            // Monto calculado a pagar
            $table->decimal('monto_pagar', 10, 2); // Renombrado de monto_aguinaldo a monto_pagar para abarcar retroactivos

            // Control administrativo
            $table->enum('estado', ['Pendiente', 'Pagado'])->default('Pendiente');
            $table->date('fecha_pago')->nullable();
            $table->string('nro_comprobante')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aguinaldos');
    }
};
