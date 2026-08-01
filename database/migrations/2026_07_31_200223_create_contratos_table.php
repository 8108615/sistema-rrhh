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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            // Relación con el empleado
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            // Tipo de contrato según legislación boliviana
            $table->enum('tipo_contrato', [
                'Indefinido',
                'Plazo Fijo',
                'Consultoría por Producto',
                'Consultoría en Línea',
                'Pasantía'
            ])->default('Indefinido');

            // Fechas clave
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable(); // Nullable porque los indefinidos no tienen fecha de fin

            // Condiciones del contrato al momento de la firma
            $table->decimal('salario_mensual', 10, 2);
            $table->string('cargo_contrato'); // El cargo escrito en el contrato

            // Archivo digitalizado (PDF escaneado con firmas)
            $table->string('archivo_pdf')->nullable();

            // Estado actual del contrato
            $table->enum('estado', ['Activo', 'Vencido', 'Finalizado', 'Anulado'])->default('Activo');

            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
