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
        Schema::create('stitching_lines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Línea 1, Línea 7, Servicio Externo (Único)
            $table->boolean('is_external_service')->default(false); // Para identificar el servicio único
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stitching_lines');
    }
};
