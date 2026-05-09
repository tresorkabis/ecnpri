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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade');
            $table->date('inspection_date');
            $table->string('type'); // Routine, Inopinée, Suivi
            $table->string('status')->default('Prévue'); // Prévue, En cours, Terminée, Rapportée
            $table->text('summary')->nullable();
            $table->timestamps();
        });

        Schema::create('inspection_inspector', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->foreignId('inspector_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_inspector');
        Schema::dropIfExists('inspections');
    }
};
