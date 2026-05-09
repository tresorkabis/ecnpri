<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Equipment;
use App\Models\RadioactiveSource;
use App\Models\UsageAuthorization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsageAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_usage_authorization(): void
    {
        $establishment = Establishment::create([
            'name' => 'Centre Radiologique',
            'category' => 'Médical',
        ]);

        $response = $this->post('/usage-authorizations', [
            'establishment_id' => $establishment->id,
            'authorization_type' => 'Radiodiagnostic médical',
            'scope' => 'Équipements',
            'issuing_authority' => 'CNPRI',
            'issued_at' => '2026-02-01',
            'expires_at' => '2027-01-31',
            'status' => 'Valide',
            'notes' => 'Autorisation pour radiologie conventionnelle.',
        ]);

        $response->assertRedirect('/usage-authorizations');
        $this->assertDatabaseHas('usage_authorizations', [
            'reference_number' => 'CNPRI/AUT/MED/2026/001',
            'authorization_type' => 'Radiodiagnostic médical',
            'scope' => 'Équipements',
        ]);
    }

    public function test_generated_reference_number_increments_by_establishment_category_and_year(): void
    {
        $establishment = Establishment::create([
            'name' => 'Mine Test',
            'category' => 'Mines',
        ]);

        UsageAuthorization::create([
            'establishment_id' => $establishment->id,
            'reference_number' => 'CNPRI/AUT/MIN/2026/001',
            'authorization_type' => 'Utilisation des sources',
            'scope' => 'Sources',
            'issued_at' => '2026-01-01',
            'status' => 'Valide',
        ]);

        $response = $this->post('/usage-authorizations', [
            'establishment_id' => $establishment->id,
            'authorization_type' => 'Utilisation des sources',
            'scope' => 'Sources',
            'issuing_authority' => 'CNPRI',
            'issued_at' => '2026-06-15',
            'status' => 'Valide',
        ]);

        $response->assertRedirect('/usage-authorizations');
        $this->assertDatabaseHas('usage_authorizations', [
            'reference_number' => 'CNPRI/AUT/MIN/2026/002',
        ]);
    }

    public function test_can_update_usage_authorization(): void
    {
        $establishment = Establishment::create([
            'name' => 'Laboratoire Minier',
            'category' => 'Industriel',
        ]);

        $authorization = UsageAuthorization::create([
            'establishment_id' => $establishment->id,
            'reference_number' => 'CNPRI/AUT/OLD/001',
            'authorization_type' => 'Recherche',
            'scope' => 'Sources',
            'status' => 'En attente',
        ]);

        $response = $this->put("/usage-authorizations/{$authorization->id}", [
            'establishment_id' => $establishment->id,
            'reference_number' => 'CNPRI/AUT/NEW/002',
            'authorization_type' => 'Radio industrielle',
            'scope' => 'Sources et Équipements',
            'issuing_authority' => 'CNPRI',
            'issued_at' => '2026-03-10',
            'expires_at' => '2027-03-09',
            'status' => 'Valide',
            'notes' => 'Autorisation mise à jour.',
        ]);

        $response->assertRedirect("/usage-authorizations/{$authorization->id}");
        $this->assertDatabaseHas('usage_authorizations', [
            'id' => $authorization->id,
            'reference_number' => 'CNPRI/AUT/NEW/002',
            'authorization_type' => 'Radio industrielle',
            'scope' => 'Sources et Équipements',
            'status' => 'Valide',
        ]);
    }

    public function test_can_list_usage_authorizations(): void
    {
        $this->seed(\Database\Seeders\CnpriSeeder::class);

        $response = $this->get('/usage-authorizations');

        $response->assertStatus(200);
        $response->assertSee("Autorisations d'Utilisation", false);
        $response->assertSee('CNPRI/AUT/MED/2026/001');
        $response->assertSee('Radiodiagnostic médical');
    }

    public function test_can_see_usage_authorization_details(): void
    {
        $establishment = Establishment::create([
            'name' => 'Centre de Recherche',
            'category' => 'Recherche',
        ]);

        $authorization = UsageAuthorization::create([
            'establishment_id' => $establishment->id,
            'reference_number' => 'CNPRI/AUT/RES/003',
            'authorization_type' => 'Recherche',
            'scope' => 'Sources',
            'issuing_authority' => 'CNPRI',
            'status' => 'Valide',
        ]);

        $response = $this->get("/usage-authorizations/{$authorization->id}");

        $response->assertStatus(200);
        $response->assertSee('CNPRI/AUT/RES/003');
        $response->assertSee('Recherche');
        $response->assertSee('Centre de Recherche');
    }

    public function test_authorization_details_show_related_sources_and_equipment(): void
    {
        $establishment = Establishment::create([
            'name' => 'Site Industriel',
            'category' => 'Industriel',
        ]);

        Equipment::create([
            'establishment_id' => $establishment->id,
            'name' => 'Générateur RX',
            'model' => 'XR-500',
            'serial_number' => 'EQ-500',
            'regulatory_number' => 'CNPRI-EQ-500',
            'status' => 'Active',
        ]);

        RadioactiveSource::create([
            'establishment_id' => $establishment->id,
            'serial_number' => 'SRC-900',
            'regulatory_number' => 'CNPRI-SRC-900',
            'isotope' => 'Cs-137',
            'initial_activity' => 1.5,
            'unit' => 'GBq',
            'activity_date' => '2026-01-01',
            'physical_form' => 'Sealed',
            'status' => 'Active',
        ]);

        $authorization = UsageAuthorization::create([
            'establishment_id' => $establishment->id,
            'reference_number' => 'CNPRI/AUT/IND/010',
            'authorization_type' => 'Radio industrielle',
            'scope' => 'Sources et Équipements',
            'status' => 'Valide',
        ]);

        $response = $this->get("/usage-authorizations/{$authorization->id}");

        $response->assertStatus(200);
        $response->assertSee('Sources et Équipements Concernés');
        $response->assertSee('Générateur RX');
        $response->assertSee('Cs-137');
        $response->assertSee('CNPRI-EQ-500');
        $response->assertSee('CNPRI-SRC-900');
    }

    public function test_can_delete_usage_authorization(): void
    {
        $establishment = Establishment::create([
            'name' => 'Clinique Test',
            'category' => 'Médical',
        ]);

        $authorization = UsageAuthorization::create([
            'establishment_id' => $establishment->id,
            'reference_number' => 'CNPRI/AUT/DEL/004',
            'authorization_type' => 'Diagnostics dentaires',
            'scope' => 'Équipements',
            'status' => 'Valide',
        ]);

        $response = $this->delete("/usage-authorizations/{$authorization->id}");

        $response->assertRedirect('/usage-authorizations');
        $this->assertDatabaseMissing('usage_authorizations', [
            'id' => $authorization->id,
        ]);
    }
}
