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
        Schema::create('motives', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Mal cosido, Falta bordado, Falta estampado (goma/tallas), Jalon, etc.
            $table->enum('type', ['costura', 'bordado', 'estampado', 'general']); // Para categorizar el motivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motives');
    }
};
