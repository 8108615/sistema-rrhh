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
        Schema::create('planillas', function (Blueprint $table) {
            $table->id();
            $table->string('mes'); // Ej: "Julio", "Agosto" o formato numérico
            $table->year('anio'); // Ej: 2026
            $table->decimal('total_pagado', 10, 2)->default(0);
            $table->enum('estado', ['Pendiente', 'Pagado'])->default('Pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planillas');
    }
};
