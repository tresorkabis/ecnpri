<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Inspection;
use App\Models\Inspector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InspectionFiltersTest extends TestCase
{
    use RefreshDatabase;

    private Establishment $perenco;
    private Establishment $braccongo;
    private Inspector $inspectorA;
    private Inspector $inspectorB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->perenco = Establishment::create([
            'name' => 'PERENCO REP',
            'province' => 'Kongo Central',
            'city' => 'Muanda',
            'category' => 'Industriel',
        ]);

        $this->braccongo = Establishment::create([
            'name' => 'BRACONGO',
            'province' => 'Kinshasa',
            'city' => 'Kinshasa',
            'category' => 'Industriel',
        ]);

        $this->inspectorA = Inspector::create([
            'name' => 'Jean Dupont',
            'employee_id' => 'INSP-FILTRE-001',
        ]);
        $this->inspectorB = Inspector::create([
            'name' => 'Marie Martin',
            'employee_id' => 'INSP-FILTRE-002',
        ]);

        // Mission réglementaire 2026-09-01, PERENCO, inspecteur A
        $a = $this->inspection($this->perenco, 'réglementaire', '2026-09-01', 'Brouillon', 'Inspection annuelle PERENCO');
        $a->inspectors()->attach($this->inspectorA->id);

        // Mission investigation 2026-10-05, BRACONGO, inspecteur B
        $b = $this->inspection($this->braccongo, 'investigation', '2026-10-05', 'En cours', 'Enquête incident source');
        $b->inspectors()->attach($this->inspectorB->id);

        // Mission inopiné 2026-08-10, BRACONGO, effectuée
        $this->inspection($this->braccongo, 'inopiné', '2026-08-10', 'Effectuée', 'Contrôle surprise');
    }

    private function inspection(Establishment $establishment, string $type, string $date, string $status, string $purpose): Inspection
    {
        return Inspection::create([
            'establishment_id' => $establishment->id,
            'start_date' => $date,
            'end_date' => $date,
            'type' => $type,
            'status' => $status,
            'purpose' => $purpose,
        ]);
    }

    public function test_page_displays_filter_controls(): void
    {
        $response = $this->get('/inspections');

        $response->assertOk();
        $response->assertSee('name="recherche"', false);
        $response->assertSee('name="etablissement_id"', false);
        $response->assertSee('name="inspecteur_id"', false);
        $response->assertSee('name="type"', false);
        $response->assertSee('name="statut"', false);
        $response->assertSee('name="date_debut"', false);
        $response->assertSee('name="date_fin"', false);
    }

    public function test_filter_by_status(): void
    {
        $response = $this->getJson('/inspections?statut=Brouillon');
        $response->assertOk()->assertJsonCount(1);
        $response->assertJsonFragment(['status' => 'Brouillon']);
    }

    public function test_filter_by_upcoming_statuses(): void
    {
        // "prevues" = Brouillon + Approuvée + En cours => 2 missions dans ce jeu de données
        $response = $this->getJson('/inspections?statut=prevues');
        $response->assertOk()->assertJsonCount(2);
    }

    public function test_filter_by_type(): void
    {
        $response = $this->getJson('/inspections?type=' . urlencode('investigation'));
        $response->assertOk()->assertJsonCount(1);
        $response->assertJsonFragment(['type' => 'investigation']);
    }

    public function test_filter_by_establishment(): void
    {
        $response = $this->getJson('/inspections?etablissement_id=' . $this->braccongo->id);
        $response->assertOk()->assertJsonCount(2);
    }

    public function test_filter_by_inspector(): void
    {
        $response = $this->getJson('/inspections?inspecteur_id=' . $this->inspectorB->id);
        $response->assertOk()->assertJsonCount(1);
        $response->assertJsonFragment(['status' => 'En cours']);
    }

    public function test_filter_by_period(): void
    {
        // Période qui ne contient que la mission en cours (2026-10-05)
        $response = $this->getJson('/inspections?date_debut=2026-10-01&date_fin=2026-10-31');
        $response->assertOk()->assertJsonCount(1);
        $response->assertJsonFragment(['type' => 'investigation']);
    }

    public function test_filter_by_search(): void
    {
        $response = $this->getJson('/inspections?recherche=' . urlencode('PERENCO'));
        $response->assertOk()->assertJsonCount(1);
        $response->assertJsonFragment(['type' => 'réglementaire']);
    }

    public function test_combined_filters(): void
    {
        // Établissement BRACONGO + statut En cours => 1 résultat
        $response = $this->getJson('/inspections?etablissement_id=' . $this->braccongo->id . '&statut=' . urlencode('En cours'));
        $response->assertOk()->assertJsonCount(1);
    }
}