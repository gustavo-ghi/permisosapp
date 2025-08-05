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
        Schema::create('app_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la app cliente, ej: "App de Contabilidad"
            $table->string('api_token', 60)->unique(); // Token único para autenticación
            $table->boolean('is_active')->default(true); // Estado de la app (activa o inactiva)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_clients');
    }
};
