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
        Schema::create('garments', function (Blueprint $table) {
            $table->id();
            // 1. Datos principales
            $table->string('pv', 5)->unique(); // Código PV (5 números aleatorios)
            $table->string('color');
            $table->boolean('is_audit')->default(false); // Auditoría (Urgente)

            // 2. Relaciones (Foreign Keys)
            $table->foreignId('client_id')->constrained('clients'); // Marca del Cliente (Ralph Lauren, etc.)
            $table->foreignId('stitching_line_id')->constrained('stitching_lines'); // Línea que lo hizo (Línea 1, Servicio Ext.)
            $table->foreignId('motive_id')->constrained('motives'); // Motivo del arreglo

            // 3. Registro de Entrega y Devolución
            $table->string('delivered_by'); // Persona que entrega la prenda para arreglo
            $table->dateTime('delivery_in_date'); // Fecha y hora de entrada
            $table->foreignId('registered_by_user_id')->constrained('users'); // Usuario que registra la entrada

            $table->string('received_by')->nullable(); // Persona que recibe la prenda de vuelta
            $table->dateTime('delivery_out_date')->nullable(); // Fecha y hora de devolución
            $table->foreignId('delivered_by_user_id')->nullable()->constrained('users'); // Usuario que registra la salida

            $table->enum('status', ['pendiente', 'entregado'])->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garments');
    }
};
