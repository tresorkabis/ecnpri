<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Inspector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InspectionReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_inspection_report()
    {
        Storage::fake('public');
        $this->seed(\Database\Seeders\CnpriSeeder::class);

        $establishment = Establishment::first();
        $inspector = Inspector::first();

        $file = UploadedFile::fake()->create('report.pdf', 100, 'application/pdf');

        $data = [
            'establishment_id' => $establishment->id,
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-01',
            'type' => 'réglementaire',
            'inspectors' => [$inspector->id],
            'summary' => 'Inspection avec rapport PDF',
            'report' => $file,
        ];

        $response = $this->post('/inspections', $data);

        $response->assertRedirect('/inspections');
        
        $this->assertDatabaseHas('inspections', [
            'summary' => 'Inspection avec rapport PDF',
        ]);

        $inspection = \App\Models\Inspection::where('summary', 'Inspection avec rapport PDF')->first();
        $this->assertNotNull($inspection->report_path);
        
        Storage::disk('public')->assertExists($inspection->report_path);
    }
}
