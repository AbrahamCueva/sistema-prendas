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
            $table->string('pv', 5);
            $table->string('color');
            $table->string('size', 10);

            // --- CAMBIOS CLAVE PARA GESTIONAR LOTES PARCIALES ---
            // Renombramos 'quantity' a 'quantity_in' (Cantidad total que entra)
            $table->unsignedSmallInteger('quantity_in')->default(1);
            // NUEVA COLUMNA: Cantidad total que ha salido del lote
            $table->unsignedSmallInteger('quantity_out')->default(0);
            // ----------------------------------------------------

            $table->boolean('is_audit')->default(false);
            $table->string('defect_photo_path', 2048)->nullable();
            $table->enum('audit_level', ['normal', 'urgente'])->default('normal');

            // 2. Relaciones (Foreign Keys)
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('stitching_line_id')->constrained('stitching_lines');
            $table->foreignId('motive_id')->constrained('motives');

            // 3. Registro de Entrada y Devolución
            $table->string('delivered_by');
            $table->dateTime('delivery_in_date');
            $table->foreignId('registered_by_user_id')->constrained('users');

            $table->string('received_by')->nullable();
            $table->dateTime('delivery_out_date')->nullable();
            $table->foreignId('delivered_by_user_id')->nullable()->constrained('users');

            // MODIFICAMOS el ENUM para incluir 'en_proceso' (Entrega parcial)
            $table->enum('status', ['pendiente', 'en_proceso', 'entregado'])->default('pendiente');

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
