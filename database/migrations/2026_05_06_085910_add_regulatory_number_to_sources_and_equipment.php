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
            $table->string('regulatory_number')->nullable()->after('serial_number');
        });

        Schema::table('radioactive_sources', function (Blueprint $table) {
            $table->string('regulatory_number')->nullable()->after('serial_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn('regulatory_number');
        });

        Schema::table('radioactive_sources', function (Blueprint $table) {
            $table->dropColumn('regulatory_number');
        });
    }
};
