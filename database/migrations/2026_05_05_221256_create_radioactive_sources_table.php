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
        Schema::create('radioactive_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade');
            $table->string('serial_number')->unique();
            $table->string('isotope'); // ex: Co-60, Cs-137, Ir-192
            $table->double('initial_activity');
            $table->string('unit'); // TBq, GBq, mCi, Ci
            $table->date('activity_date'); // Date de mesure de l'activité initiale
            $table->string('physical_form')->default('Sealed'); // Sealed, Unsealed
            $table->string('category')->nullable(); // Catégorie AIEA (1-5)
            $table->string('status')->default('Active'); // Active, Stored, Disposed, Lost
            $table->text('location_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radioactive_sources');
    }
};
