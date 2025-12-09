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
            // 1. Datos principales (MODIFICADOS)
            $table->string('pv', 5); // Código PV (5 números aleatorios) - YA NO ES UNIQUE
            $table->string('color');
            $table->string('size', 10); // NUEVO: Campo para la talla (S, M, L, 32, 40)
            $table->unsignedSmallInteger('quantity')->default(1); // NUEVO: Cantidad de prendas en el lote
            $table->boolean('is_audit')->default(false);
            $table->enum('audit_level', ['normal', 'urgente'])->default('normal'); // MODIFICADO: Nivel de Auditoría/Urgencia (Elimina is_audit)

            // 2. Relaciones (Foreign Keys)
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('stitching_line_id')->constrained('stitching_lines');
            $table->foreignId('motive_id')->constrained('motives');

            // 3. Registro de Entrega y Devolución
            $table->string('delivered_by');
            $table->dateTime('delivery_in_date');
            $table->foreignId('registered_by_user_id')->constrained('users');

            $table->string('received_by')->nullable();
            $table->dateTime('delivery_out_date')->nullable();
            $table->foreignId('delivered_by_user_id')->nullable()->constrained('users');

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
