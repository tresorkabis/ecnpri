<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Establishment;
use App\Models\Inspection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_loads_correctly()
    {
        // Créer des données de test
        $est = Establishment::create([
            'name' => 'Test Establishment',
            'address' => '123 Test St',
            'city' => 'Kinshasa',
            'contact_person' => 'John Doe',
        ]);

        Inspection::create([
            'establishment_id' => $est->id,
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(6)->toDateString(),
            'type' => 'réglementaire',
            'status' => 'Brouillon',
            'mission_order_number' => 'OM-123',
            'object' => 'Test Object',
        ]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Tableau de Bord');
        $response->assertSee('Test Establishment');
        $response->assertSee('Brouillon');
    }
}
