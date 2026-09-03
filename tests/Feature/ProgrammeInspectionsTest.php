<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Inspection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgrammeInspectionsTest extends TestCase
{
    use RefreshDatabase;

    private array $establishments = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->establishments['perenco'] = Establishment::create([
            'name' => 'PERENCO REP',
            'province' => 'Kongo Central',
            'city' => 'Muanda',
            'address' => 'Moanda',
            'category' => 'Industriel',
        ]);

        $this->establishments['braccongo'] = Establishment::create([
            'name' => 'BRACONGO',
            'province' => 'Kinshasa',
            'city' => 'Kinshasa',
            'address' => 'Kinshasa',
            'category' => 'Industriel',
        ]);

        $this->establishments['bunia'] = Establishment::create([
            'name' => 'AEROPORT DE BUNIA',
            'province' => 'Ituri',
            'city' => 'Bunia',
            'address' => 'Bunia',
            'category' => 'Autre',
        ]);

        // 2e semestre 2026
        $this->inspection('réglementaire', '2026-09-01', 'perenco');
        $this->inspection('réglementaire', '2026-09-05', 'braccongo');
        $this->inspection('investigation', '2026-10-02', 'bunia');

        // Hors période : ne doit jamais apparaître
        $this->inspection('réglementaire', '2026-03-01', 'bunia');
    }

    private function inspection(string $type, string $date, string $key): Inspection
    {
        return Inspection::create([
            'establishment_id' => $this->establishments[$key]->id,
            'start_date' => $date,
            'end_date' => $date,
            'type' => $type,
            'status' => 'Brouillon',
        ]);
    }

    public function test_programme_page_lists_inspections_grouped_by_type_and_zone(): void
    {
        $response = $this->get('/inspections/programme?annee=2026&semestre=2');

        $response->assertOk();
        $response->assertSee('Proposition du Programme des Inspections');
        $response->assertSee('INSPECTIONS REGLEMENTAIRES');
        $response->assertSee('INSPECTIONS D\'INVESTIGATIONS');
        $response->assertSee('KONGO-CENTRAL');
        $response->assertSee('PERENCO REP');
        $response->assertSee('AEROPORT DE BUNIA');
        $response->assertSee('ITURI');
        // Plus de regroupement fourre-tout : chaque province réelle est affichée
        $response->assertDontSee('AUTRES PROVINCES');

        // L'inspection de mars (hors 2e semestre) ne doit pas être listée
        $response->assertDontSee('01/03/2026');

        // La signature configurée est affichée
        $response->assertSee(config('cnpri.signature_name'));
        $response->assertSee(config('cnpri.signature_title'));
    }

    public function test_programme_word_export_returns_valid_docx(): void
    {
        $response = $this->get('/inspections/programme/export?annee=2026&semestre=2');

        $response->assertOk();
        $this->assertStringContainsString(
            '.docx',
            $response->headers->get('content-disposition', '')
        );

        // Le fichier téléchargé doit être une archive ZIP OOXML contenant word/document.xml
        $file = $response->baseResponse->getFile();

        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($file->getPathname()) === true);
        $this->assertNotFalse($zip->locateName('word/document.xml'));

        // Le nom et la fonction configurés figurent dans le corps du document
        $documentXml = $zip->getFromName('word/document.xml');
        $text = html_entity_decode(strip_tags((string) $documentXml));
        $this->assertStringContainsString(config('cnpri.signature_name'), $text);
        $this->assertStringContainsString(config('cnpri.signature_title'), $text);

        $zip->close();
    }
}