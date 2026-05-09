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
            $table->text('purpose')->nullable()->after('type');
            $table->text('methodology')->nullable()->after('summary');
            $table->text('conclusion')->nullable()->after('methodology');
            $table->string('site_representative')->nullable()->after('conclusion');
            $table->string('site_representative_title')->nullable()->after('site_representative');
            $table->foreignId('team_leader_id')->nullable()->constrained('inspectors')->onDelete('set null')->after('establishment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropForeign(['team_leader_id']);
            $table->dropColumn([
                'purpose',
                'methodology',
                'conclusion',
                'site_representative',
                'site_representative_title',
                'team_leader_id'
            ]);
        });
    }
};
