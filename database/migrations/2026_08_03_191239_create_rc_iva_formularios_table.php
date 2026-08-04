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
        Schema::create('rc_iva_formularios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->string('periodo_mes'); // Ej: '2026-08' o 'Agosto 2026'
            $table->decimal('sueldo_neto', 10, 2)->default(0); // Sueldo menos AFPs
            $table->decimal('dos_salarios_minimos', 10, 2)->default(0); // El monto exento legal
            $table->decimal('impuesto_rc_iva', 10, 2)->default(0); // 13% sobre la diferencia
            $table->decimal('saldo_fisco_periodo_anterior', 10, 2)->default(0);
            $table->decimal('total_facturas_presentadas', 10, 2)->default(0); // Total de compras con F110
            $table->decimal('credito_fiscal_facturas', 10, 2)->default(0); // 13% de las facturas
            $table->decimal('saldo_a_favor_dependiente', 10, 2)->default(0); // Se arrastra al siguiente mes
            $table->decimal('impuesto_retenido_fisco', 10, 2)->default(0); // Lo que se le descuenta en boleta
            $table->string('estado')->default('Pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rc_iva_formularios');
    }
};
