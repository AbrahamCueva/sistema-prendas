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
            $table->string('pv', 5);
            $table->string('color');
            // CAMBIO CLAVE: Reemplazamos 'size' por 'sizes' (JSON)
            $table->json('sizes')->nullable();

            $table->unsignedSmallInteger('quantity_in')->default(1);
            $table->unsignedSmallInteger('quantity_out')->default(0);
            $table->boolean('is_audit')->default(false);
            $table->string('defect_photo_path', 2048)->nullable();
            $table->enum('audit_level', ['normal', 'urgente'])->default('normal');

            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('stitching_line_id')->constrained('stitching_lines');
            $table->foreignId('motive_id')->constrained('motives');

            $table->string('delivered_by');
            $table->dateTime('delivery_in_date');
            $table->foreignId('registered_by_user_id')->constrained('users');

            $table->string('received_by')->nullable();
            $table->dateTime('delivery_out_date')->nullable();
            $table->foreignId('delivered_by_user_id')->nullable()->constrained('users');

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
