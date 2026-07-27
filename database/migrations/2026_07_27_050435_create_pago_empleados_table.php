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
        Schema::create('pago_empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->string('mes');
            $table->string('anio');
            $table->date('fecha_pago');

            // Montos
            $table->decimal('salario_base', 10, 2);
            $table->decimal('bonos', 10, 2)->default(0);
            $table->decimal('descuento_afp', 10, 2)->default(0);
            $table->decimal('anticipos', 10, 2)->default(0);
            $table->decimal('otros_descuentos', 10, 2)->default(0);
            $table->decimal('total_pagar', 10, 2);

            // Transacción y detalles
            $table->string('metodo_pago')->default('Transferencia'); // Transferencia, Efectivo, Cheque
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
        Schema::dropIfExists('pago_empleados');
    }
};
