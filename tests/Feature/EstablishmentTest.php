<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Establishment;
use App\Models\UsageAuthorization;

class EstablishmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_establishment_with_contact_title(): void
    {
        $data = [
            'name' => 'Nouvel Établissement',
            'category' => 'Médical',
            'contact_name' => 'Jean Dupont',
            'contact_title' => 'Directeur Général',
            'province' => 'Kinshasa',
            'city' => 'Gombe'
        ];

        $response = $this->post('/establishments', $data);

        $response->assertRedirect('/establishments');
        $this->assertDatabaseHas('establishments', [
            'name' => 'Nouvel Établissement',
            'contact_name' => 'Jean Dupont',
            'contact_title' => 'Directeur Général'
        ]);
    }

    public function test_can_update_establishment_with_contact_title(): void
    {
        $establishment = Establishment::create([
            'name' => 'Ancien Nom',
            'category' => 'Industriel'
        ]);

        $data = [
            'name' => 'Nom Mis à Jour',
            'category' => 'Industriel',
            'contact_title' => 'Chef de Service'
        ];

        $response = $this->put("/establishments/{$establishment->id}", $data);

        $response->assertRedirect("/establishments/{$establishment->id}");
        $this->assertDatabaseHas('establishments', [
            'id' => $establishment->id,
            'contact_title' => 'Chef de Service'
        ]);
    }

    public function test_can_see_contact_title_in_show_page(): void
    {
        $establishment = Establishment::create([
            'name' => 'Établissement Test',
            'category' => 'Recherche',
            'contact_name' => 'Alice Martin',
            'contact_title' => 'Chercheuse Principale'
        ]);

        $response = $this->get("/establishments/{$establishment->id}");

        $response->assertStatus(200);
        $response->assertSee('Chercheuse Principale');
        $response->assertSee('Alice Martin');
    }

    public function test_can_see_usage_authorizations_in_show_page(): void
    {
        $establishment = Establishment::create([
            'name' => 'Établissement Autorisé',
            'category' => 'Recherche',
        ]);

        UsageAuthorization::create([
            'establishment_id' => $establishment->id,
            'reference_number' => 'CNPRI/AUT/RES/003',
            'authorization_type' => 'Recherche',
            'scope' => 'Sources',
            'issuing_authority' => 'CNPRI',
            'status' => 'Valide',
        ]);

        $response = $this->get("/establishments/{$establishment->id}");

        $response->assertStatus(200);
        $response->assertSee("Autorisations d'utilisation", false);
        $response->assertSee('CNPRI/AUT/RES/003');
        $response->assertSee('Recherche');
        $response->assertSee('Sources');
    }
}
