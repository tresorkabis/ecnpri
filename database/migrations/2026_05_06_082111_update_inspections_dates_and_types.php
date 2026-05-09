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
        Schema::table('inspections', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('team_leader_id');
            $table->date('end_date')->nullable()->after('start_date');
        });

        // Migrate existing inspection_date to start_date and end_date
        DB::table('inspections')->update([
            'start_date' => DB::raw('inspection_date'),
            'end_date' => DB::raw('inspection_date'),
        ]);

        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn('inspection_date');
            $table->string('type')->default('réglementaire')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->date('inspection_date')->nullable()->after('team_leader_id');
        });

        DB::table('inspections')->update([
            'inspection_date' => DB::raw('start_date'),
        ]);

        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
