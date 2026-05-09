<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('status')->default('Brouillon')->change();
        });

        // Mise à jour des anciens statuts vers les nouveaux
        DB::table('inspections')->where('status', 'Prévue')->update(['status' => 'Approuvée']);
        DB::table('inspections')->where('status', 'Terminée')->update(['status' => 'Effectuée']);
        DB::table('inspections')->where('status', 'Rapportée')->update(['status' => 'Effectuée']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('status')->default('Prévue')->change();
        });
    }
};
