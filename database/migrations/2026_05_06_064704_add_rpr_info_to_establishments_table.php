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
        Schema::table('establishments', function (Blueprint $table) {
            $table->string('rpr_name')->nullable();
            $table->string('rpr_phone')->nullable();
            $table->string('rpr_email')->nullable();
            $table->string('rpr_accreditation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('establishments', function (Blueprint $table) {
            $table->dropColumn(['rpr_name', 'rpr_phone', 'rpr_email', 'rpr_accreditation']);
        });
    }
};
