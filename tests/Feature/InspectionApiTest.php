<?php

namespace Tests\Feature;
use App\Models\Inspection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InspectionApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test establishments list.
     */
    public function test_can_list_establishments(): void
    {
        $this->seed(\Database\Seeders\CnpriSeeder::class);
        $response = $this->get('/establishments', ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonCount(4);
        $response->assertJsonFragment(['name' => 'Clinique Ngaliema']);
    }

    /**
     * Test inspections list.
     */
    public function test_can_list_inspections(): void
    {
        $this->seed(\Database\Seeders\CnpriSeeder::class);
        $response = $this->get('/inspections', ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonCount(5); // 2 effectuées, 1 approuvée, 1 brouillon, 1 en cours (PERENCO)
    }

    /**
     * Test can schedule an inspection.
     */
    public function test_can_schedule_an_inspection(): void
    {
        $this->seed(\Database\Seeders\CnpriSeeder::class);
        
        $data = [
            'establishment_id' => 1,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-02',
            'type' => 'réglementaire',
            'inspectors' => [1, 2],
            'summary' => 'Test de planification'
        ];

        $response = $this->post('/inspections', $data, ['Accept' => 'application/json']);

        $response->assertStatus(201);
        $this->assertDatabaseHas('inspections', [
            'start_date' => '2026-07-01 00:00:00',
            'end_date' => '2026-07-02 00:00:00',
            'status' => 'Brouillon'
        ]);
    }

    /**
     * Test can update an inspection.
     */
    public function test_can_update_an_inspection(): void
    {
        $this->seed(\Database\Seeders\CnpriSeeder::class);
        
        $data = [
            'establishment_id' => 1,
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-01',
            'type' => 'inopiné',
            'status' => 'Approuvée',
            'inspectors' => [1],
            'summary' => 'Résumé mis à jour'
        ];

        // On prend une inspection en statut Brouillon (id=4 dans le seeder)
        $response = $this->put('/inspections/4', $data, ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('inspections', [
            'id' => 4,
            'start_date' => '2026-08-01 00:00:00',
            'status' => 'Approuvée',
            'summary' => 'Résumé mis à jour'
        ]);
    }

    /**
     * Test cannot update a completed inspection.
     */
    public function test_cannot_update_a_completed_inspection(): void
    {
        $this->seed(\Database\Seeders\CnpriSeeder::class);
        
        $data = [
            'establishment_id' => 1,
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-01',
            'type' => 'réglementaire',
            'status' => 'Effectuée',
            'inspectors' => [1],
        ];

        // On essaie de modifier une inspection Effectuée (id=1 dans le seeder)
        $response = $this->put('/inspections/1', $data, ['Accept' => 'application/json']);

        $response->assertStatus(403);
    }

    /**
     * Test visibility of edit button in inspection details.
     */
    public function test_edit_button_visibility_in_details(): void
    {
        $this->seed(\Database\Seeders\CnpriSeeder::class);

        // Inspection Effectuée (id=1) - Ne devrait pas avoir le bouton
        $response = $this->get('/inspections/1');
        $response->assertStatus(200);
        $response->assertDontSee('Modifier la Mission');

        // Inspection Brouillon (id=4) - Devrait avoir le bouton
        $response = $this->get('/inspections/4');
        $response->assertStatus(200);
        $response->assertSee('Modifier la Mission');

        // Inspection En cours (id=5 - PERENCO) - Devrait avoir le bouton
        $response = $this->get('/inspections/5');
        $response->assertStatus(200);
        $response->assertSee('Modifier la Mission');
    }

    /**
     * Test can update an "In Progress" inspection.
     */
    public function test_can_update_in_progress_inspection(): void
    {
        $this->seed(\Database\Seeders\CnpriSeeder::class);
        
        $data = [
            'establishment_id' => 4, // PERENCO
            'start_date' => '2026-05-05',
            'end_date' => '2026-05-10', // Date changée
            'type' => 'réglementaire',
            'status' => 'En cours',
            'inspectors' => [5, 6, 7, 8, 9, 10],
            'summary' => 'Résumé mis à jour pour PERENCO'
        ];

        // Inspection 5 est "En cours" (PERENCO)
        $response = $this->put('/inspections/5', $data, ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('inspections', [
            'id' => 5,
            'end_date' => '2026-05-10 00:00:00',
            'summary' => 'Résumé mis à jour pour PERENCO'
        ]);
    }

    /**
     * Test inspection approval.
     */
    public function test_can_approve_inspection(): void
    {
        $this->seed(\Database\Seeders\CnpriSeeder::class);
        
        // Inspection Brouillon (id=4)
        $response = $this->post('/inspections/4/approve', [], ['Accept' => 'application/json']);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('inspections', [
            'id' => 4,
            'status' => 'Approuvée'
        ]);
    }

    /**
     * Test visibility of approval button.
     */
    public function test_approval_button_visibility(): void
    {
        $this->seed(\Database\Seeders\CnpriSeeder::class);

        // Brouillon (id=4) - Devrait voir le bouton
        $response = $this->get('/inspections/4');
        $response->assertSee('Approuver la Mission');

        // Approuvée (id=3) - Ne devrait pas voir le bouton
        $response = $this->get('/inspections/3');
        $response->assertDontSee('Approuver la Mission');
    }
}
