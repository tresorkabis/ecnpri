<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Equipment;
use App\Models\RadioactiveSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetRegulatoryNumberGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_equipment_regulatory_number_is_generated_from_establishment(): void
    {
        $establishment = Establishment::create([
            'name' => 'Clinique Générale',
            'category' => 'Médical',
        ]);

        $response = $this->post('/equipment', [
            'establishment_id' => $establishment->id,
            'name' => 'Appareil de radiographie',
            'installation_date' => '2026-01-20',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/equipment');
        $this->assertDatabaseHas('equipment', [
            'name' => 'Appareil de radiographie',
            'regulatory_number' => 'CNPRI-EQ-MED-2026-001',
        ]);
    }

    public function test_equipment_regulatory_number_increments_by_category_and_year(): void
    {
        $establishment = Establishment::create([
            'name' => 'Site Industriel',
            'category' => 'Industriel',
        ]);

        Equipment::create([
            'establishment_id' => $establishment->id,
            'name' => 'Équipement existant',
            'regulatory_number' => 'CNPRI-EQ-IND-2026-001',
            'status' => 'Active',
        ]);

        $response = $this->post('/equipment', [
            'establishment_id' => $establishment->id,
            'name' => 'Nouvel équipement',
            'installation_date' => '2026-06-10',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/equipment');
        $this->assertDatabaseHas('equipment', [
            'name' => 'Nouvel équipement',
            'regulatory_number' => 'CNPRI-EQ-IND-2026-002',
        ]);
    }

    public function test_source_regulatory_number_is_generated_from_establishment(): void
    {
        $establishment = Establishment::create([
            'name' => 'Centre de Recherche',
            'category' => 'Recherche',
        ]);

        $response = $this->post('/radioactive-sources', [
            'establishment_id' => $establishment->id,
            'serial_number' => 'SRC-001',
            'isotope' => 'Co-60',
            'initial_activity' => 2.3,
            'unit' => 'GBq',
            'activity_date' => '2026-03-15',
            'physical_form' => 'Sealed',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/radioactive-sources');
        $this->assertDatabaseHas('radioactive_sources', [
            'serial_number' => 'SRC-001',
            'regulatory_number' => 'CNPRI-SRC-RES-2026-001',
        ]);
    }

    public function test_source_regulatory_number_increments_by_category_and_year(): void
    {
        $establishment = Establishment::create([
            'name' => 'Mine pilote',
            'category' => 'Mines',
        ]);

        RadioactiveSource::create([
            'establishment_id' => $establishment->id,
            'serial_number' => 'SRC-EXIST',
            'regulatory_number' => 'CNPRI-SRC-MIN-2026-001',
            'isotope' => 'Cs-137',
            'initial_activity' => 1.2,
            'unit' => 'GBq',
            'activity_date' => '2026-01-01',
            'physical_form' => 'Sealed',
            'status' => 'Active',
        ]);

        $response = $this->post('/radioactive-sources', [
            'establishment_id' => $establishment->id,
            'serial_number' => 'SRC-NEW',
            'isotope' => 'Ir-192',
            'initial_activity' => 0.8,
            'unit' => 'GBq',
            'activity_date' => '2026-07-01',
            'physical_form' => 'Sealed',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/radioactive-sources');
        $this->assertDatabaseHas('radioactive_sources', [
            'serial_number' => 'SRC-NEW',
            'regulatory_number' => 'CNPRI-SRC-MIN-2026-002',
        ]);
    }
}
