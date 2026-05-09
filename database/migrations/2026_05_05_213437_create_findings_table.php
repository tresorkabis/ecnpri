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
        Schema::create('findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->string('severity')->default('Moyenne'); // Faible, Moyenne, Élevée, Critique
            $table->text('recommendation')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('Ouvert'); // Ouvert, Résolu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('findings');
    }
};
