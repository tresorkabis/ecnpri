<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Establishment;
use App\Models\Equipment;
use App\Models\Inspector;
use App\Models\Inspection;
use App\Models\Finding;
use App\Models\User;
use App\Models\RadioactiveSource;
use App\Models\UsageAuthorization;
use Illuminate\Support\Facades\Hash;

class CnpriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un utilisateur admin
        $admin = User::create([
            'name' => 'Admin CNPRI',
            'email' => 'admin@cnpri.cd',
            'password' => Hash::make('password'),
        ]);

        // Création des inspecteurs
        $inspector1 = Inspector::create([
            'name' => 'Jean-Paul Kasongo',
            'grade' => 'Inspecteur Principal',
            'employee_id' => 'INSP-001',
            'specialization' => 'Radioprotection médicale',
        ]);

        $inspector2 = Inspector::create([
            'name' => 'Marie-Thérèse Kabedi',
            'grade' => 'Inspecteur de 1ère classe',
            'employee_id' => 'INSP-002',
            'specialization' => 'Sécurité nucléaire et Mines',
        ]);

        $inspector3 = Inspector::create([
            'name' => 'Baleke Bashige',
            'grade' => 'Inspecteur de 1ère classe',
            'employee_id' => 'INSP-003',
            'specialization' => 'Radioprotection industrielle',
        ]);

        $inspector4 = Inspector::create([
            'name' => 'Madoli Tanga Pauline',
            'grade' => 'Inspecteur de 2ème classe',
            'employee_id' => 'INSP-004',
            'specialization' => 'Protection radiologique',
        ]);

        $inspector5 = Inspector::create([
            'name' => 'Bibiche WANGUNA CHING-CHEY',
            'grade' => 'Inspecteur Principal',
            'employee_id' => 'INSP-005',
            'specialization' => 'Protection radiologique',
        ]);

        $inspector6 = Inspector::create([
            'name' => 'Lepetit BONGA KIMAKIMA',
            'grade' => 'Inspecteur de 1ère classe',
            'employee_id' => 'INSP-006',
            'specialization' => 'Sûreté nucléaire',
        ]);

        $inspector7 = Inspector::create([
            'name' => 'Rachel MUSADILA ONKUN',
            'grade' => 'Inspecteur de 2ème classe',
            'employee_id' => 'INSP-007',
            'specialization' => 'Radioprotection médicale',
        ]);

        $inspector8 = Inspector::create([
            'name' => 'Trésor KABISAYI NTALE MAYO',
            'grade' => 'Inspecteur de 2ème classe',
            'employee_id' => 'INSP-008',
            'specialization' => 'Sécurité nucléaire',
        ]);

        $inspector9 = Inspector::create([
            'name' => 'Jean-Claude TEMBELE',
            'grade' => 'Inspecteur Assistant',
            'employee_id' => 'INSP-009',
            'specialization' => 'Protection radiologique',
        ]);

        $inspector10 = Inspector::create([
            'name' => 'César MAYUMBU MAPELA',
            'grade' => 'Inspecteur de 1ère classe',
            'employee_id' => 'INSP-010',
            'specialization' => 'Radioprotection industrielle',
        ]);

        // Création des établissements
        $e1 = Establishment::create([
            'name' => 'Clinique Ngaliema',
            'address' => 'Avenue de la Clinique, Gombe',
            'city' => 'Kinshasa',
            'province' => 'Kinshasa',
            'category' => 'Médical',
            'contact_name' => 'Dr. Mukendi',
            'contact_title' => 'Médecin Chef de Staff',
            'rpr_name' => 'Jean-Pierre Lelo',
            'rpr_phone' => '+243 812 345 678',
            'rpr_email' => 'lelo@ngaliema.cd',
            'rpr_accreditation' => 'CNPRI/RPR/MED/2023/042',
        ]);

        $e2 = Establishment::create([
            'name' => 'Tenke Fungurume Mining (TFM)',
            'address' => 'Route Likasi',
            'city' => 'Fungurume',
            'province' => 'Lualaba',
            'category' => 'Mines',
            'contact_name' => 'Ing. Smith',
            'contact_title' => 'Responsable HSE',
            'rpr_name' => 'Alain Katumbwe',
            'rpr_phone' => '+243 998 765 432',
            'rpr_email' => 'akatumbwe@tfm.cd',
            'rpr_accreditation' => 'CNPRI/RPR/MIN/2022/015',
        ]);

        $e3 = Establishment::create([
            'name' => 'PPC Barnet (Cimenterie)',
            'address' => 'Localité Malanga',
            'city' => 'Kimpese',
            'province' => 'Kongo Central',
            'category' => 'Industriel',
            'contact_name' => 'M. Jean Lema',
            'contact_title' => 'Directeur de Production',
            'rpr_name' => 'Dieudonné KABILA',
            'rpr_phone' => '+243 845 123 456',
            'rpr_email' => 'd.kabila@ppcbarnet.cd',
            'rpr_accreditation' => 'CNPRI/RPR/IND/2024/008',
        ]);

        $e4 = Establishment::create([
            'name' => 'PERENCO',
            'address' => 'Base de Muanda',
            'city' => 'Muanda',
            'province' => 'Kongo Central',
            'category' => 'Industriel',
            'contact_name' => 'Michel Lefebvre',
            'contact_title' => 'Responsable HSE',
            'rpr_name' => 'Pierre Ndongala',
            'rpr_phone' => '+243 821 555 999',
            'rpr_email' => 'p.ndongala@cd.perenco.com',
            'rpr_accreditation' => 'CNPRI/RPR/IND/2024/089',
        ]);

        UsageAuthorization::create([
            'establishment_id' => $e1->id,
            'reference_number' => 'CNPRI/AUT/MED/2026/001',
            'authorization_type' => 'Radiodiagnostic médical',
            'scope' => 'Équipements',
            'issuing_authority' => 'CNPRI',
            'issued_at' => '2026-01-15',
            'expires_at' => '2027-01-14',
            'status' => 'Valide',
            'notes' => 'Autorisation d’exploiter les équipements de radiodiagnostic de l’établissement.',
        ]);

        UsageAuthorization::create([
            'establishment_id' => $e2->id,
            'reference_number' => 'CNPRI/AUT/MIN/2025/014',
            'authorization_type' => 'Utilisation des jauges',
            'scope' => 'Sources et Équipements',
            'issuing_authority' => 'CNPRI',
            'issued_at' => '2025-08-01',
            'expires_at' => '2026-07-31',
            'status' => 'Valide',
            'notes' => 'Couvre les jauges nucléaires et les sources scellées utilisées sur le site minier.',
        ]);

        UsageAuthorization::create([
            'establishment_id' => $e3->id,
            'reference_number' => 'CNPRI/AUT/IND/2024/088',
            'authorization_type' => 'Utilisation des sources',
            'scope' => 'Sources',
            'issuing_authority' => 'CNPRI',
            'issued_at' => '2024-05-20',
            'expires_at' => '2025-05-19',
            'status' => 'Expirée',
            'notes' => 'Renouvellement administratif attendu avant poursuite de l’utilisation de la source Co-60.',
        ]);

        // Équipements PERENCO
        Equipment::create([
            'establishment_id' => $e4->id,
            'name' => 'Générateur des rayonnements ionisants',
            'manufacturer' => 'Stephanix',
            'model' => 'HF-N',
            'serial_number' => 'GD210019S700',
            'regulatory_number' => 'CNPRI-EQ-2026-010',
            'voltage_max' => '440',
            'installation_date' => '2025-01-01',
            'use_case' => 'Radio conv',
            'status' => 'Active',
        ]);

        Equipment::create([
            'establishment_id' => $e4->id,
            'name' => 'Tube radiogène',
            'manufacturer' => 'Stephanix',
            'model' => 'N/A',
            'serial_number' => 'B-DTEO830',
            'regulatory_number' => 'CNPRI-EQ-2026-011',
            'intensity_max' => 'N/A',
            'filtration' => 'N/A',
            'installation_date' => '2025-01-01',
            'status' => 'Active',
        ]);

        // Équipements
        Equipment::create([
            'establishment_id' => $e1->id,
            'name' => 'Scanner CT Philips',
            'model' => 'Brilliance 64',
            'serial_number' => 'PH-12345',
            'regulatory_number' => 'CNPRI-EQ-2024-001',
            'manufacturer' => 'Philips Medical',
            'status' => 'Active',
        ]);

        Equipment::create([
            'establishment_id' => $e2->id,
            'name' => 'Jauge de densité nucléaire',
            'model' => 'Troxler 3440',
            'serial_number' => 'TX-9876',
            'regulatory_number' => 'CNPRI-EQ-2024-012',
            'manufacturer' => 'Troxler',
            'status' => 'Active',
        ]);

        Equipment::create([
            'establishment_id' => $e3->id,
            'name' => 'Jauge de niveau gamma',
            'model' => 'LB 440',
            'serial_number' => 'SN-BER-778',
            'regulatory_number' => 'CNPRI-EQ-2024-045',
            'manufacturer' => 'Berthold Technologies',
            'status' => 'Active',
        ]);

        // Sources Radioactives
        RadioactiveSource::create([
            'establishment_id' => $e2->id,
            'serial_number' => 'SRC-TFM-001',
            'regulatory_number' => 'CNPRI-SRC-2023-005',
            'isotope' => 'Cs-137',
            'initial_activity' => 1.85,
            'unit' => 'GBq',
            'activity_date' => '2023-01-15',
            'physical_form' => 'Sealed',
            'category' => '4',
            'status' => 'Active',
            'location_details' => 'Silo de stockage 4',
        ]);

        RadioactiveSource::create([
            'establishment_id' => $e3->id,
            'serial_number' => 'SRC-PPC-099',
            'regulatory_number' => 'CNPRI-SRC-2024-088',
            'isotope' => 'Co-60',
            'initial_activity' => 0.74,
            'unit' => 'GBq',
            'activity_date' => '2024-05-20',
            'physical_form' => 'Sealed',
            'category' => '5',
            'status' => 'Active',
            'location_details' => 'Ligne de production 2',
        ]);

        // Inspection PPC (Inspirée du PDF)
        $inspPpc = Inspection::create([
            'establishment_id' => $e3->id,
            'team_leader_id' => $inspector3->id,
            'start_date' => now()->subDays(6)->toDateString(),
            'end_date' => now()->subDays(5)->toDateString(),
            'type' => 'réglementaire',
            'purpose' => 'Inspection réglementaire de radioprotection et de sûreté des sources de rayonnements ionisants.',
            'status' => 'Effectuée',
            'authorized_by' => 'Le Secrétaire Général à la Recherche Scientifique',
            'summary' => 'La mission avait pour but de s’assurer de la conformité des installations de PPC Barnet aux normes de radioprotection.',
            'methodology' => "1. Séance d'ouverture avec la direction.\n2. Contrôle documentaire (Inventaire, Registres).\n3. Mesures de débit de dose sur site.\n4. Vérification de la signalisation et du balisage.",
            'conclusion' => 'L\'établissement dispose d\'une infrastructure solide, mais des améliorations sont nécessaires concernant le suivi dosimétrique du personnel.',
            'site_representative' => 'M. Dieudonné KABILA',
            'site_representative_title' => 'Responsable Sécurité (HSE)',
        ]);

        $inspPpc->inspectors()->attach([$inspector3->id, $inspector4->id]);

        Finding::create([
            'inspection_id' => $inspPpc->id,
            'description' => 'Certains panneaux de signalisation "Zone Surveillée" sont décolorés et peu lisibles.',
            'severity' => 'Faible',
            'recommendation' => 'Remplacer les panneaux de signalisation par des modèles résistants aux intempéries.',
            'deadline' => now()->addMonths(1),
            'status' => 'Ouvert',
        ]);

        Finding::create([
            'inspection_id' => $inspPpc->id,
            'description' => 'Retard dans la transmission des rapports de dosimétrie passive du dernier trimestre.',
            'severity' => 'Moyenne',
            'recommendation' => 'S\'assurer de la collecte et de l\'envoi régulier des dosimètres au laboratoire agréé.',
            'deadline' => now()->addDays(15),
            'status' => 'Ouvert',
        ]);

        // Inspection
        $insp1 = Inspection::create([
            'establishment_id' => $e1->id,
            'team_leader_id' => $inspector1->id,
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->subDays(10)->toDateString(),
            'type' => 'réglementaire',
            'status' => 'Effectuée',
            'authorized_by' => 'Le Responsable de Division Radioprotection',
            'summary' => 'Inspection annuelle de radioprotection.',
        ]);

        $insp1->inspectors()->attach([$inspector1->id]);

        // Inspection programmée (Future)
        $insp2 = Inspection::create([
            'establishment_id' => $e2->id,
            'start_date' => now()->addDays(20)->toDateString(),
            'end_date' => now()->addDays(21)->toDateString(),
            'type' => 'inopiné',
            'status' => 'Approuvée',
            'authorized_by' => 'Le Directeur du CNPRI',
            'summary' => 'Vérification surprise de la gestion des sources scellées.',
        ]);

        $insp2->inspectors()->attach([$inspector1->id, $inspector2->id]);

        // Inspection en Brouillon
        $insp3 = Inspection::create([
            'establishment_id' => $e1->id,
            'start_date' => now()->addDays(30)->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'type' => 'réglementaire',
            'status' => 'Brouillon',
            'summary' => 'Inspection de suivi pour vérifier les corrections.',
        ]);

        $insp3->inspectors()->attach([$inspector4->id]);

        // Inspection PERENCO (Nouvelle mission demandée)
        $inspPerenco = Inspection::create([
            'establishment_id' => $e4->id,
            'team_leader_id' => $inspector5->id, // Bibiche WANGUNA CHING-CHEY
            'start_date' => '2026-05-05',
            'end_date' => '2026-05-08',
            'type' => 'réglementaire',
            'purpose' => 'Inspection réglementaire de radioprotection et de sûreté des installations pétrolières de PERENCO.',
            'status' => 'En cours',
            'authorized_by' => 'Le Directeur du CNPRI',
            'summary' => 'Contrôle périodique des sources radioactives utilisées dans les activités de forage et de diagraphie.',
        ]);

        $inspPerenco->inspectors()->attach([
            $inspector5->id,
            $inspector6->id,
            $inspector7->id,
            $inspector8->id,
            $inspector9->id,
            $inspector10->id
        ]);
    }
}
