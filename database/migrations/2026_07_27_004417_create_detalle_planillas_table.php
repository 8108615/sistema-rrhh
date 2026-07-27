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
        Schema::create('detalle_planillas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planilla_id')->constrained('planillas')->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->decimal('salario_base', 10, 2);
            $table->decimal('bonos', 10, 2)->default(0);
            $table->decimal('descuentos', 10, 2)->default(0);
            $table->decimal('liquido_pagable', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_planillas');
    }
};
