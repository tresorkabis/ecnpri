<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth;

class InspectionProgramController extends Controller
{
    /**
     * En-têtes de section par type d'inspection (ordre d'affichage).
     */
    protected const TYPE_HEADERS = [
        'réglementaire' => 'INSPECTIONS REGLEMENTAIRES',
        'investigation' => 'INSPECTIONS D\'INVESTIGATIONS',
        'inopiné' => 'INSPECTIONS INOPINEES',
    ];

    /** Ordre d'affichage des zones de tournée pour chaque type. */
    protected const ZONE_ORDER = [
        'réglementaire' => ['Kongo-Central', 'Kinshasa', 'Autres provinces'],
        'investigation' => ['Kinshasa', 'Kongo-Central', 'Autres provinces'],
        'inopiné' => ['Kinshasa', 'Kongo-Central', 'Autres provinces'],
    ];

    /**
     * Affiche le programme des inspections (proposé) pour un semestre donné.
     */
    public function index(Request $request)
    {
        $annee = (int) $request->query('annee', now()->format('Y'));
        $semestre = (int) ($request->query('semestre', 2) === '2' ? 2 : 1);
        $statut = $request->query('statut', 'prevues');

        $inspections = $this->buildGroups($annee, $semestre, $statut);

        return view('inspections.programme', compact('annee', 'semestre', 'statut', 'inspections'));
    }

    /**
     * Génère et télécharge le document Word (.docx) du programme.
     */
    public function export(Request $request)
    {
        $annee = (int) $request->query('annee', now()->format('Y'));
        $semestre = (int) ($request->query('semestre', 2) === '2' ? 2 : 1);
        $statut = $request->query('statut', 'prevues');

        $inspections = $this->buildGroups($annee, $semestre, $statut);

        if ($inspections->isEmpty()) {
            return redirect()->route('inspections.programme', $request->query())
                ->with('error', 'Aucune inspection dans cette période, impossible de générer le document.');
        }

        $phpWord = new PhpWord();
        $phpWord->getDocInfo()
            ->setTitle($this->titleFor($annee, $semestre))
            ->setCreator('CNPRI - Application de Gestion des Inspections');

        $section = $phpWord->addSection([
            'pageSizeW' => 11906,
            'pageSizeH' => 16838,
            'marginTop' => 850,
            'marginBottom' => 850,
            'marginLeft' => 850,
            'marginRight' => 850,
        ]);

        // Titre principal
        $section->addText(
            $this->titleFor($annee, $semestre),
            ['bold' => true, 'size' => 14],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 240]
        );

        $roman = ['I', 'II', 'III', 'IV'];

        foreach ($inspections as $index => $groupe) {
            // Section : type d'inspection
            $section->addText(
                ($roman[$index] ?? ($index + 1)) . '. ' . $this->headerForType($groupe['type']),
                ['bold' => true, 'size' => 12],
                ['alignment' => Jc::LEFT, 'spaceBefore' => 200, 'spaceAfter' => 120]
            );

            foreach ($groupe['zones'] as $zone) {
                // Sous-section : zone de tournée / province
                $section->addText(
                    strtoupper($zone['nom']),
                    ['bold' => true, 'size' => 11],
                    ['alignment' => Jc::LEFT, 'spaceBefore' => 120, 'spaceAfter' => 80]
                );

                $this->addProgramTable($section, $zone['inspections']);
            }
        }

        // Date et signature
        $section->addTextBreak(2);
        $lastDate = $this->lastInspectionDate($inspections);
        $section->addText(
            'Fait à ' . config('cnpri.signature_ville') . ', le ' . ($lastDate ? $lastDate->format('d/m/Y') : '…/…/' . $annee),
            ['size' => 11],
            ['alignment' => Jc::RIGHT, 'spaceBefore' => 120]
        );
        $section->addTextBreak(2);
        $section->addText(config('cnpri.signature_name'), ['size' => 11, 'bold' => true], ['alignment' => Jc::RIGHT]);
        $section->addText(config('cnpri.signature_title'), ['size' => 11], ['alignment' => Jc::RIGHT]);

        // Écriture du document
        $tempFile = tempnam(sys_get_temp_dir(), 'cnpri_programme_');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

                return response()->download($tempFile, $this->titleFor($annee, $semestre) . '.docx')
            ->deleteFileAfterSend(true);
    }

    /**
     * Exporte le programme en PDF.
     */
    public function exportPdf(Request $request)
    {
        $annee = (int) $request->query('annee', now()->format('Y'));
        $semestre = (int) ($request->query('semestre', 2) === '2' ? 2 : 1);
        $statut = $request->query('statut', 'prevues');

        $inspections = $this->buildGroups($annee, $semestre, $statut);

        // Aplatir la collection pour le tableau simple du PDF
        $flat = collect();
        foreach ($inspections as $groupe) {
            foreach ($groupe['zones'] as $zone) {
                foreach ($zone['inspections'] as $item) {
                    $flat[] = $item['inspection'];
                }
            }
        }

        $pdf = \PDF::loadView('inspections.programme_pdf', compact('annee', 'semestre', 'inspections', 'flat'));

        return $pdf->download($this->titleFor($annee, $semestre) . '.pdf');
    }

    /**
     * Exporte le programme en Excel (.xlsx).
     */
    public function exportXlsx(Request $request)
    {
        $annee = (int) $request->query('annee', now()->format('Y'));
        $semestre = (int) ($request->query('semestre', 2) === '2' ? 2 : 1);
        $statut = $request->query('statut', 'prevues');

        $inspections = $this->buildGroups($annee, $semestre, $statut);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Programme Inspections');

        // En-tête
        $sheet->setCellValue('A1', 'Proposition du Programme des Inspections - ' . $annee . ' / S' . $semestre);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $headers = ['N°', 'Date', 'Installation', 'Localisation', 'Type', 'Inspecteurs', 'Statut'];
        $col = 'A';
        foreach ($headers as $header) {
            $cell = $col++ . '3';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                        $sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('4B5563');
        }

        // Données
        $row = 4;
        $num = 1;
        foreach ($inspections as $groupe) {
            foreach ($groupe['zones'] as $zone) {
                foreach ($zone['inspections'] as $item) {
                    $inspection = $item['inspection'];
                    $sheet->setCellValue('A' . $row, $num++);
                    $sheet->setCellValue('B' . $row, optional($inspection->start_date)->format('d/m/Y') ?? '—');
                    $sheet->setCellValue('C' . $row, $inspection->establishment->name ?? '—');
                    $sheet->setCellValue('D' . $row, $inspection->establishment->address ?? ($inspection->establishment->city ?? '—'));
                    $sheet->setCellValue('E' . $row, ucfirst($groupe['type']));
                    $sheet->setCellValue('F' . $row, implode(', ', $inspection->inspectors->pluck('name')->toArray()));
                    $sheet->setCellValue('G' . $row, ucfirst($inspection->status));
                    $row++;
                }
            }
        }

        // Colonnes ajustées
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->freezePane('A4');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $tempFile = tempnam(sys_get_temp_dir(), 'cnpri_programme_excel_');
        $writer->save($tempFile);

        return response()->download($tempFile, $this->titleFor($annee, $semestre) . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    /**
     * Construit le programme : type -> zones -> inspections.
     */
    protected function buildGroups(int $annee, int $semestre, string $statut): \Illuminate\Support\Collection
    {
        [$debut, $fin] = $this->periodRange($annee, $semestre);

        $query = Inspection::with(['establishment', 'inspectors'])
            ->where('start_date', '>=', $debut)
            ->where('start_date', '<=', $fin)
            ->orderBy('start_date');

        if ($statut !== 'toutes') {
            $query->whereIn('status', ['Brouillon', 'Approuvée', 'En cours']);
        }

        $items = $query->get()
            ->filter(fn (Inspection $inspection) => $inspection->establishment !== null)
            ->map(fn (Inspection $inspection) => [
                'inspection' => $inspection,
                'zone' => $this->zoneOf($inspection),
            ])
            ->values();

        $groupes = collect();

        foreach ($this->filterTypesPresent($items) as $type) {
            $byType = $items->filter(fn ($item) => $item['inspection']->type === $type)->values();
            $zoneNames = $byType->pluck('zone')->unique();

            // Tri selon l'ordre prédéfini, puis par première date de la zone, puis nom
            $order = self::ZONE_ORDER[$type] ?? [];
            $zones = $zoneNames->sortBy(function ($nom) use ($order, $byType) {
                $position = array_search($nom, $order, true);
                $position = $position === false ? count($order) : $position;
                $minDate = $byType->where('zone', $nom)
                    ->min(fn ($item) => $item['inspection']->start_date ? $item['inspection']->start_date->format('Y-m-d') : '9999-12-31');

                return [$position, $minDate, $nom];
            });

            $groupes->push([
                'type' => $type,
                'zones' => $zones->map(function ($nom) use ($byType) {
                    $zoneItems = $byType->where('zone', $nom)
                        ->sortBy(function ($item) {
                            $inspection = $item['inspection'];

                            return [
                                $inspection->start_date ? $inspection->start_date->format('Y-m-d') : '9999-12-31',
                                $this->localisation($inspection),
                            ];
                        })
                        ->values();

                    return [
                        'nom' => $nom,
                        'inspections' => $zoneItems->map(function ($item, $numero) {
                            return [
                                'numero' => $numero + 1,
                                'inspection' => $item['inspection'],
                            ];
                        }),
                    ];
                })->values(),
            ]);
        }

        return $groupes;
    }

    /**
     * Retourne les types d'inspection présents, dans l'ordre d'affichage.
     */
    protected function filterTypesPresent(\Illuminate\Support\Collection $items): array
    {
        return array_values(array_filter(
            array_keys(self::TYPE_HEADERS),
            fn ($type) => $items->contains(fn ($item) => $item['inspection']->type === $type)
        ));
    }

    /**
     * Détermine la zone de tournée (regroupement logistique) d'une inspection.
     */
    protected function zoneOf(Inspection $inspection): string
    {
        $province = $inspection->establishment->province
            ?: ($inspection->establishment->city ?: 'Autres provinces');

        $normalized = strtolower(trim($province));

        if ($normalized === 'kinshasa') {
            return 'Kinshasa';
        }

        if (in_array($normalized, [
            'kongo central', 'kongo-central', 'kongocentral', 'kongo',
            'bas-congo', 'bas congo',
        ], true)) {
            return 'Kongo-Central';
        }

        return 'Autres provinces';
    }

    /**
     * Localisation (colonne "LOCALISATION") d'une inspection.
     */
    protected function localisation(Inspection $inspection): string
    {
        $establishment = $inspection->establishment;

        return $establishment->address
            ?: ($establishment->city
                ?: ($establishment->province ?: '—'));
    }

    /**
     * Plage de dates [début, fin] pour un semestre donné.
     */
    protected function periodRange(int $annee, int $semestre): array
    {
        if ($semestre === 2) {
            return [$annee . '-07-01', $annee . '-12-31'];
        }

        return [$annee . '-01-01', $annee . '-06-30'];
    }

    /**
     * Titre documentaire du programme.
     */
    protected function titleFor(int $annee, int $semestre): string
    {
        $libelle = $semestre === 2 ? 'DEUXIEME' : 'PREMIER';

        return 'PROPOSITION DU PROGRAMME DES INSPECTIONS DU ' . $libelle . ' SEMESTRE ' . $annee;
    }

    /**
     * En-tête de section affiché pour un type d'inspection.
     */
    protected function headerForType(string $type): string
    {
        return self::TYPE_HEADERS[$type] ?? strtoupper($type);
    }

    /**
     * Date de la dernière inspection du programme (pour la signature).
     */
    protected function lastInspectionDate($groupes)
    {
        $dates = $groupes->flatMap(function ($groupe) {
            return $groupe['zones']->flatMap(function ($zone) {
                return $zone['inspections']->map(
                    fn ($item) => $item['inspection']->end_date
                )->filter();
            });
        });

        return $dates->max();
    }

    /**
     * Ajoute un tableau du programme à la section Word en cours.
     */
    protected function addProgramTable($section, $items): void
    {
        $headers = ['N°', 'DATE', 'INSTALLATION', 'LOCALISATION', 'INSPECTEURS'];
        $widths = [500, 1100, 3300, 2250, 3056];

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        $table->getStyle()->setUnit(TblWidth::TWIP);
        $table->getStyle()->setWidth(array_sum($widths));

        $headerBold = ['bold' => true, 'size' => 9];
        $cellFont = ['size' => 9];
        $centerParagraph = ['alignment' => Jc::CENTER];

        // Ligne d'en-têtes
        $table->addRow(320);
        foreach ($headers as $index => $header) {
            $table->addCell($widths[$index], ['valign' => 'center'])
                ->addText($header, $headerBold, $centerParagraph);
        }

        // Lignes de données
        foreach ($items as $item) {
            $inspection = $item['inspection'];
            $table->addRow();

            $table->addCell($widths[0])->addText((string) $item['numero'], $cellFont, $centerParagraph);
            $table->addCell($widths[1])->addText(
                $inspection->start_date ? $inspection->start_date->format('d/m') : '—',
                $cellFont,
                $centerParagraph
            );

            $installationCell = $table->addCell($widths[2]);
            $installationCell->addText($inspection->establishment->name, $cellFont);

            $table->addCell($widths[3])->addText(strtoupper($this->localisation($inspection)), $cellFont);

            $inspectorsCell = $table->addCell($widths[4]);
            foreach ($inspection->inspectors as $inspector) {
                $inspectorsCell->addText($inspector->name, $cellFont);
            }
        }
    }
}