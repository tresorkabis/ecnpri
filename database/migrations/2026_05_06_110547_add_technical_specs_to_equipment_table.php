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
        Schema::table('equipment', function (Blueprint $table) {
            $table->string('voltage_max')->nullable(); // T. max (V)
            $table->string('intensity_max')->nullable(); // I. Max (mA)
            $table->string('use_case')->nullable(); // Utilisation
            $table->string('filtration')->nullable(); // Filtration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn(['voltage_max', 'intensity_max', 'use_case', 'filtration']);
        });
    }
};
