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

    public function test_sort_by_date_asc(): void
    {
        $response = $this->getJson('/inspections?tri=date&sens=asc');
        $response->assertOk();
        $data = $response->json();

        // La mission la plus ancienne est l'inopiné du 2026-08-10 (BRACONGO)
        $this->assertSame('inopiné', $data[0]['type']);
        $this->assertEquals('2026-08-10', substr($data[0]['start_date'], 0, 10));
    }

    public function test_sort_by_establishment_desc(): void
    {
        $response = $this->getJson('/inspections?tri=etablissement&sens=desc');
        $response->assertOk();
        $data = $response->json();

        // Ordre décroissant des noms : PERENCO REP > BRACONGO
        $this->assertSame('PERENCO REP', $data[0]['establishment']['name']);
    }

    public function test_page_displays_sort_links(): void
    {
        $response = $this->get('/inspections');

        $response->assertOk();
        $response->assertSee('tri=date', false);
        $response->assertSee('tri=etablissement', false);
        $response->assertSee('tri=type', false);
        $response->assertSee('tri=statut', false);
    }

    public function test_pagination_splits_results_and_is_preserved_with_filters(): void
    {
        // Établissement "récent" : 15 missions récentes (remplissent la première page)
        $recent = Establishment::create([
            'name' => 'ETAB RECENT',
            'province' => 'Kinshasa',
            'city' => 'Kinshasa',
            'category' => 'Autre',
        ]);
        for ($i = 1; $i <= 15; $i++) {
            $this->inspection($recent, 'réglementaire', '2026-11-' . str_pad((string) $i, 2, '0', STR_PAD_LEFT), 'Brouillon', 'Mission récente ' . $i);
        }

        // Établissement "ancien" : 1 mission 2026. Ce sera la dernière => page 2
        $ancien = Establishment::create([
            'name' => 'ETAB ANCIEN',
            'province' => 'Kinshasa',
            'city' => 'Kinshasa',
            'category' => 'Autre',
        ]);
        $this->inspection($ancien, 'investigation', '2026-05-01', 'Effectuée', 'Mission ancienne');

        $page1 = $this->get('/inspections');
        $page1->assertOk();
        $page1->assertSee('ETAB RECENT');
        $page1->assertSee('page=2', false);

        // "ETAB ANCIEN" n'apparaît que dans le menu du filtre (1 occurrence), pas dans le tableau
        // "ETAB RECENT" apparaît 16 fois : 15 lignes + 1 option du filtre
        $htmlPage1 = $page1->getContent();
        $this->assertSame(1, substr_count($htmlPage1, 'ETAB ANCIEN'));
        $this->assertSame(16, substr_count($htmlPage1, 'ETAB RECENT'));

        $page2 = $this->get('/inspections?page=2');
        $page2->assertOk();
        $page2->assertSee('ETAB ANCIEN');

        // Page 2 : ETAB ANCIEN en ligne + option du filtre, ETAB RECENT uniquement dans le filtre
        $htmlPage2 = $page2->getContent();
        $this->assertSame(2, substr_count($htmlPage2, 'ETAB ANCIEN'));
        $this->assertSame(1, substr_count($htmlPage2, 'ETAB RECENT'));
    }

    public function test_grouped_mode_orders_by_type_then_province_with_headers(): void
    {
        $etabKin = Establishment::create(['name' => 'HOPITAL GROUPE KIN', 'province' => 'Kinshasa', 'city' => 'Kinshasa']);
        $etabKat = Establishment::create(['name' => 'USINE GROUPE KAT', 'province' => 'Haut-Katanga', 'city' => 'Lubumbashi']);
        $etabEqu = Establishment::create(['name' => 'CLINIQUE GROUPE EQU', 'province' => 'Équateur', 'city' => 'Mbandaka']);

        Inspection::create(['establishment_id' => $etabKin->id, 'type' => 'investigation', 'status' => 'Approuvée', 'purpose' => 'Test', 'start_date' => '2026-08-01', 'end_date' => '2026-08-02', 'authorized_by' => 'Le Président du CNPRI']);
        Inspection::create(['establishment_id' => $etabKat->id, 'type' => 'réglementaire', 'status' => 'Approuvée', 'purpose' => 'Test', 'start_date' => '2026-08-01', 'end_date' => '2026-08-02', 'authorized_by' => 'Le Président du CNPRI']);
        Inspection::create(['establishment_id' => $etabEqu->id, 'type' => 'réglementaire', 'status' => 'Approuvée', 'purpose' => 'Test', 'start_date' => '2026-08-01', 'end_date' => '2026-08-02', 'authorized_by' => 'Le Président du CNPRI']);

        $response = $this->get('/inspections?groupe=1');
        $response->assertOk();
        $response->assertSee('GROUPE KIN', false);
        $response->assertSee('GROUPE KAT', false);
        $response->assertSee('GROUPE EQU', false);

        // En-têtes de groupe
        $response->assertSee("I. INSPECTIONS REGLEMENTAIRES", false);
        $response->assertSee("II. INSPECTIONS D&#039;INVESTIGATION", false);
        $response->assertSee('Haut-Katanga', false);
        $response->assertSee('Équateur', false);
        $response->assertSee('groupé par type puis province', false);

        // Ordre (vérifié uniquement dans le corps du tableau, pas dans les filtres) :
        // Réglementaire (Équateur avant Haut-Katanga) puis Investigation (Kinshasa)
        $tbody = mb_strstr($response->getContent(), '<tbody');
        $this->assertTrue(
            mb_strpos($tbody, 'GROUPE EQU') < mb_strpos($tbody, 'GROUPE KAT')
            && mb_strpos($tbody, 'GROUPE KAT') < mb_strpos($tbody, 'GROUPE KIN')
        );
        $this->assertTrue(
            mb_strpos($tbody, "I. INSPECTIONS REGLEMENTAIRES") < mb_strpos($tbody, "D&#039;INVESTIGATION")
        );

        // Le bouton de retour à la liste simple est présent
        $response->assertSee('Afficher en liste simple', false);
    }
}