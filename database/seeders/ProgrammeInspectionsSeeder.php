<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Establishment;
use App\Models\Inspector;
use App\Models\Inspection;

/**
 * Programme des inspections du deuxième semestre 2026 (proposition),
 * fidèle au document "PROPOSITION DU PROGRAMME DES INSPECTIONS
 * DU DEUXIEME SEMESTRE 2026.docx". Exécution idempotente.
 */
class ProgrammeInspectionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedInspectors();
        $this->seedEstablishments();

        $this->seedInspections('réglementaire', [
            ['etab' => 'PERENCO REP', 'date' => '2026-09-01'],
            ['etab' => 'LEDYA', 'date' => '2026-09-02'],
            ['etab' => 'PPC BARNET', 'date' => '2026-09-05', 'inspectors' => ['NZEREKA MBAYITIRAKI', 'LUBATA TABALA']],
            ['etab' => 'CIMKO', 'date' => '2026-09-07', 'inspectors' => ['ATIBU SALUMU', 'MBOIKANA MPETI']],
            ['etab' => 'CILU', 'date' => '2026-09-08', 'inspectors' => ['ATIBU SALUMU', 'MBOIKANA MPETI']],
            ['etab' => 'CIMENT SOLL', 'date' => '2026-09-09'],
            ['etab' => 'BRACONGO', 'date' => '2026-09-01'],
            ['etab' => 'BRALIMA', 'date' => '2026-09-02'],
            ['etab' => 'SEP CONGO', 'date' => '2026-09-05'],
            ['etab' => 'RAYON SERVICES', 'date' => '2026-09-07'],
            ['etab' => 'OFFICE DES ROUTES', 'date' => '2026-09-08'],
            ['etab' => 'NOVOTEL', 'date' => '2026-09-09'],
            ['etab' => 'PULLMAN HOTEL', 'date' => '2026-09-10'],
            ['etab' => 'HOTEL FLEUVE CONGO', 'date' => '2026-09-11'],
            ['etab' => 'HOTEL HILTON', 'date' => '2026-09-12'],
            ['etab' => 'HOTEL IBIS', 'date' => '2026-09-13'],
            ['etab' => 'AERODROME DE NDOLO (RVA)', 'date' => '2026-09-14'],
        ]);

        $this->seedInspections('investigation', [
            ['etab' => 'OVD', 'date' => '2026-10-01'],
            ['etab' => 'ACGT', 'date' => '2026-10-02'],
            ['etab' => 'MW AFRITEC', 'date' => '2026-10-03'],
            ['etab' => 'SAFRIMEX', 'date' => '2026-10-04'],
            ['etab' => 'CHINA HIGHWAY FIRST ENGINEERING', 'date' => '2026-10-05'],
            ['etab' => 'BEST BUILDING COMPANY', 'date' => '2026-10-06'],
            ['etab' => 'CREC 7', 'date' => '2026-10-07'],
            ['etab' => 'CREC 8', 'date' => '2026-10-08'],
            ['etab' => 'SYNO HYDRO', 'date' => '2026-10-09'],
            ['etab' => 'ADI CONSTRUCT', 'date' => '2026-10-10'],
            ['etab' => 'ENTREPRISE GENERALE MALTA FORREST', 'date' => '2026-10-11'],
            ['etab' => 'ONATRA (PORT DE MATADI)', 'date' => '2026-10-12'],
            ['etab' => 'MODERN CONSTRUCTION COMPANY', 'date' => '2026-10-13'],
            ['etab' => 'DHL', 'date' => '2026-10-14'],
            ['etab' => 'ZONE ECCONOMIQUE SPECIALE (MALUKU)', 'date' => '2026-10-15'],
            ['etab' => 'ONATRA (PORT DE MATADI)', 'date' => '2026-10-01'],
            ['etab' => 'AEROPORT DE BUNIA', 'date' => '2026-10-02'],
            ['etab' => 'KIBALI', 'date' => '2026-10-03'],
        ]);

        $this->command->info('Programme des inspections du 2e semestre 2026 prêt.');
    }

    /** Crée les 4 inspecteurs nommés dans la proposition, si absents. */
    protected function seedInspectors(): void
    {
        $inspecteurs = [
            'NZEREKA MBAYITIRAKI' => 'INSP-PRG-011',
            'LUBATA TABALA' => 'INSP-PRG-012',
            'ATIBU SALUMU' => 'INSP-PRG-013',
            'MBOIKANA MPETI' => 'INSP-PRG-014',
        ];

        foreach ($inspecteurs as $name => $employeeId) {
            Inspector::firstOrCreate(
                ['name' => $name],
                ['employee_id' => $employeeId, 'specialization' => 'Radioprotection industrielle']
            );
        }
    }

    /** Crée les établissements cités dans la proposition, si absents. */
    protected function seedEstablishments(): void
    {
        $etablissements = [
            ['name' => 'PERENCO REP', 'province' => 'Kongo Central', 'city' => 'Muanda', 'address' => 'Moanda', 'category' => 'Industriel'],
            ['name' => 'LEDYA', 'province' => 'Kongo Central', 'city' => 'Muanda', 'address' => 'Moanda', 'category' => 'Industriel'],
            ['name' => 'PPC BARNET', 'province' => 'Kongo Central', 'city' => 'Kimpese', 'address' => 'Kimpese (Malanga)', 'category' => 'Industriel'],
            ['name' => 'CIMKO', 'province' => 'Kongo Central', 'city' => 'Kimpese', 'address' => 'Kimpese (Minkala)', 'category' => 'Industriel'],
            ['name' => 'CILU', 'province' => 'Kongo Central', 'city' => 'Lukala', 'address' => 'Lukala', 'category' => 'Industriel'],
            ['name' => 'CIMENT SOLL', 'province' => 'Kongo Central', 'city' => 'Lukala', 'address' => 'Lukala', 'category' => 'Industriel'],
            ['name' => 'BRACONGO', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Industriel'],
            ['name' => 'BRALIMA', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Industriel'],
            ['name' => 'SEP CONGO', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Industriel'],
            ['name' => 'RAYON SERVICES', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'OFFICE DES ROUTES', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'NOVOTEL', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'PULLMAN HOTEL', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'HOTEL FLEUVE CONGO', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'HOTEL HILTON', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'HOTEL IBIS', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'AERODROME DE NDOLO (RVA)', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'OVD', 'province' => 'Kinshasa', 'city' => 'Limete', 'address' => 'Kinshasa/Limete', 'category' => 'Autre'],
            ['name' => 'ACGT', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'MW AFRITEC', 'province' => 'Kinshasa', 'city' => 'Limete', 'address' => 'Kinshasa/Limete', 'category' => 'Autre'],
            ['name' => 'SAFRIMEX', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'CHINA HIGHWAY FIRST ENGINEERING', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'BEST BUILDING COMPANY', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'CREC 7', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'CREC 8', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'SYNO HYDRO', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'ADI CONSTRUCT', 'province' => 'Kinshasa', 'city' => 'Kalamu', 'address' => 'Kinshasa/Kalamu', 'category' => 'Autre'],
            ['name' => 'ENTREPRISE GENERALE MALTA FORREST', 'province' => 'Kinshasa', 'city' => 'Limete', 'address' => 'Kinshasa/Limete', 'category' => 'Autre'],
            ['name' => 'ONATRA (PORT DE MATADI)', 'province' => 'Kongo Central', 'city' => 'Matadi', 'address' => 'Port de Matadi', 'category' => 'Autre'],
            ['name' => 'MODERN CONSTRUCTION COMPANY', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'DHL', 'province' => 'Kinshasa', 'city' => 'Kinshasa', 'address' => 'Kinshasa', 'category' => 'Autre'],
            ['name' => 'ZONE ECCONOMIQUE SPECIALE (MALUKU)', 'province' => 'Kinshasa', 'city' => 'Maluku', 'address' => 'Kinshasa/Maluku', 'category' => 'Autre'],
            ['name' => 'AEROPORT DE BUNIA', 'province' => 'Ituri', 'city' => 'Bunia', 'address' => 'Bunia', 'category' => 'Autre'],
            ['name' => 'KIBALI', 'province' => 'Haut-Uele', 'city' => 'Kibali', 'address' => 'Kibali', 'category' => 'Mines'],
        ];

        foreach ($etablissements as $data) {
            Establishment::firstOrCreate(['name' => $data['name']], $data);
        }
    }

    /**
     * Planifie les inspections d'un type donné (une par établissement et date).
     */
    protected function seedInspections(string $type, array $missions): void
    {
        foreach ($missions as $mission) {
            $establishment = Establishment::where('name', $mission['etab'])->first();
            if (!$establishment) {
                $this->command->warn('Établissement introuvable : ' . $mission['etab']);
                continue;
            }

            $inspection = Inspection::firstOrCreate(
                [
                    'establishment_id' => $establishment->id,
                    'start_date' => $mission['date'],
                ],
                [
                    'establishment_id' => $establishment->id,
                    'start_date' => $mission['date'],
                    'end_date' => $mission['date'],
                    'type' => $type,
                    'status' => 'Brouillon',
                    'authorized_by' => 'Le Directeur du CNPRI',
                    'purpose' => ucfirst($type) . ' programmée dans le cadre du programme semestriel des inspections.',
                ]
            );

            if (!empty($mission['inspectors'])) {
                $ids = Inspector::whereIn('name', $mission['inspectors'])->pluck('id')->all();
                $inspection->inspectors()->syncWithoutDetaching($ids);
            }
        }
    }
}