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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('ci')->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->enum('genero', ['Masculino', 'Femenino', 'Otro'])->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('email')->nullable();
            $table->string('nro_cuenta')->nullable();
            $table->string('banco')->nullable();
            $table->string('celular_referencia')->nullable();
            $table->string('parentesco_referencia')->nullable();
            $table->decimal('salario', 10, 2)->default(0.00);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
