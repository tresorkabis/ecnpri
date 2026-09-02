<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\Establishment;
use App\Models\Inspector;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            'recherche' => trim((string) $request->query('recherche', '')),
            'statut' => (string) $request->query('statut', ''),
            'type' => (string) $request->query('type', ''),
            'etablissement_id' => (string) $request->query('etablissement_id', ''),
            'inspecteur_id' => (string) $request->query('inspecteur_id', ''),
            'date_debut' => (string) $request->query('date_debut', ''),
            'date_fin' => (string) $request->query('date_fin', ''),
            'tri' => in_array($request->query('tri', 'date'), ['date', 'etablissement', 'type', 'statut'], true)
                ? (string) $request->query('tri')
                : 'date',
            'sens' => $request->query('sens', 'desc') === 'asc' ? 'asc' : 'desc',
        ];

        $query = $this->buildIndexQuery($filters);

        if ($request->wantsJson()) {
            return response()->json($query->get());
        }

        $inspections = $query->paginate(15)->withQueryString();

        $statuts = [
            'prevues' => 'Prévues (Brouillon, Approuvée, En cours)',
            'Brouillon' => 'Brouillon',
            'Approuvée' => 'Approuvée',
            'En cours' => 'En cours',
            'Effectuée' => 'Effectuée',
            'Annulée' => 'Annulée',
        ];
        $types = [
            'réglementaire' => 'Réglementaire',
            'investigation' => 'Investigation',
            'inopiné' => 'Inopiné',
        ];
        $etablissements = Establishment::orderBy('name')->get();
        $inspecteurs = Inspector::orderBy('name')->get();

        return view('inspections.index', compact('inspections', 'filters', 'statuts', 'types', 'etablissements', 'inspecteurs'));
    }

    /**
     * Construit la requête des inspections avec les filtres et le tri.
     */
    protected function buildIndexQuery(array $filters): \Illuminate\Database\Eloquent\Builder
    {
        $query = Inspection::with(['establishment', 'inspectors']);

        // Recherche libre : nom de l'établissement ou objet de la mission
        if ($filters['recherche'] !== '') {
            $recherche = $filters['recherche'];
            $query->where(function ($q) use ($recherche) {
                $q->whereHas('establishment', fn ($sub) => $sub->where('name', 'like', "%{$recherche}%"))
                  ->orWhere('purpose', 'like', "%{$recherche}%");
            });
        }

        // Statut (avec le pseudo-statut "prevues" regroupant les missions planifiées)
        if ($filters['statut'] !== '') {
            if ($filters['statut'] === 'prevues') {
                $query->whereIn('status', ['Brouillon', 'Approuvée', 'En cours']);
            } else {
                $query->where('status', $filters['statut']);
            }
        }

        if ($filters['type'] !== '' && $filters['type'] !== 'tous') {
            $query->where('type', $filters['type']);
        }

        if ($filters['etablissement_id'] !== '' && $filters['etablissement_id'] !== 'tous') {
            $query->where('establishment_id', $filters['etablissement_id']);
        }

        if ($filters['inspecteur_id'] !== '' && $filters['inspecteur_id'] !== 'tous') {
            $query->whereHas('inspectors', fn ($q) => $q->where('inspectors.id', $filters['inspecteur_id']));
        }

        if ($filters['date_debut'] !== '') {
            $query->where('start_date', '>=', $filters['date_debut']);
        }

        if ($filters['date_fin'] !== '') {
            $query->where('start_date', '<=', $filters['date_fin']);
        }

        // Tri (whitelist de colonnes pour éviter toute injection SQL)
        $sens = $filters['sens'];

        if ($filters['tri'] === 'etablissement') {
            $query->leftJoin('establishments', 'establishments.id', '=', 'inspections.establishment_id')
                ->select('inspections.*')
                ->orderBy('establishments.name', $sens)
                ->orderBy('inspections.start_date', $sens);
        } else {
            $colonnes = [
                'date' => 'inspections.start_date',
                'type' => 'inspections.type',
                'statut' => 'inspections.status',
            ];
            $colonne = $colonnes[$filters['tri']] ?? 'inspections.start_date';
            $query->orderBy($colonne, $sens);

            // Tri secondaire de stabilité (toujours par date)
            if ($colonne !== 'inspections.start_date') {
                $query->orderBy('inspections.start_date', 'desc');
            }
        }

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $establishments = Establishment::orderBy('name')->get();
        $inspectors = Inspector::orderBy('name')->get();

        return view('inspections.create', compact('establishments', 'inspectors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
            'team_leader_id' => 'nullable|exists:inspectors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|in:réglementaire,inopiné,investigation',
            'purpose' => 'nullable|string',
            'inspectors' => 'required|array',
            'inspectors.*' => 'exists:inspectors,id',
            'summary' => 'nullable|string',
            'methodology' => 'nullable|string',
            'conclusion' => 'nullable|string',
            'site_representative' => 'nullable|string',
            'site_representative_title' => 'nullable|string',
            'authorized_by' => 'nullable|string',
            'report' => 'nullable|file|mimes:pdf|max:10240', // PDF max 10MB
        ]);

        $reportPath = null;
        if ($request->hasFile('report')) {
            $reportPath = $request->file('report')->store('reports', 'public');
        }

        $inspection = Inspection::create([
            'establishment_id' => $validated['establishment_id'],
            'team_leader_id' => $validated['team_leader_id'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'purpose' => $validated['purpose'] ?? null,
            'status' => 'Brouillon',
            'authorized_by' => $validated['authorized_by'] ?? config('cnpri.authorization_authority'),
            'summary' => $validated['summary'] ?? null,
            'methodology' => $validated['methodology'] ?? null,
            'conclusion' => $validated['conclusion'] ?? null,
            'site_representative' => $validated['site_representative'] ?? null,
            'site_representative_title' => $validated['site_representative_title'] ?? null,
            'report_path' => $reportPath,
        ]);

        $inspection->inspectors()->attach($validated['inspectors']);

        if ($request->wantsJson()) {
            return response()->json($inspection, 201);
        }

        return redirect()->route('inspections.index')->with('success', 'Inspection programmée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inspection = Inspection::with([
            'establishment.equipment',
            'establishment.radioactiveSources',
            'inspectors',
            'findings',
            'teamLeader'
        ])->findOrFail($id);
        $previousInspection = Inspection::where('id', '<', $inspection->id)
            ->orderByDesc('id')
            ->first();
        $nextInspection = Inspection::where('id', '>', $inspection->id)
            ->orderBy('id')
            ->first();
        
        if (request()->wantsJson()) {
            return response()->json($inspection);
        }

        return view('inspections.show', compact('inspection', 'previousInspection', 'nextInspection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $inspection = Inspection::with('inspectors')->findOrFail($id);

        if (!in_array($inspection->status, ['Brouillon', 'Approuvée', 'En cours'])) {
            return redirect()->route('inspections.show', $id)
                ->with('error', 'Seules les missions en Brouillon, Approuvée ou En cours peuvent être modifiées.');
        }

        $establishments = Establishment::orderBy('name')->get();
        $inspectors = Inspector::orderBy('name')->get();
        $statuses = ['Brouillon', 'Approuvée', 'En cours', 'Effectuée', 'Annulée'];

        return view('inspections.edit', compact('inspection', 'establishments', 'inspectors', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inspection = Inspection::findOrFail($id);

        if (!in_array($inspection->status, ['Brouillon', 'Approuvée', 'En cours'])) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Modification non autorisée pour ce statut.'], 403);
            }
            return redirect()->route('inspections.show', $id)
                ->with('error', 'Seules les missions en Brouillon, Approuvée ou En cours peuvent être modifiées.');
        }

        $validated = $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
            'team_leader_id' => 'nullable|exists:inspectors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|in:réglementaire,inopiné,investigation',
            'purpose' => 'nullable|string',
            'status' => 'required|string|in:Brouillon,Approuvée,En cours,Effectuée,Annulée',
            'authorized_by' => 'nullable|string',
            'inspectors' => 'required|array',
            'inspectors.*' => 'exists:inspectors,id',
            'summary' => 'nullable|string',
            'methodology' => 'nullable|string',
            'conclusion' => 'nullable|string',
            'site_representative' => 'nullable|string',
            'site_representative_title' => 'nullable|string',
            'report' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('report')) {
            $reportPath = $request->file('report')->store('reports', 'public');
            $inspection->report_path = $reportPath;
        }

        $inspection->update([
            'establishment_id' => $validated['establishment_id'],
            'team_leader_id' => $validated['team_leader_id'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'purpose' => $validated['purpose'] ?? null,
            'status' => $validated['status'],
            'authorized_by' => $validated['authorized_by'] ?? config('cnpri.authorization_authority'),
            'summary' => $validated['summary'] ?? null,
            'methodology' => $validated['methodology'] ?? null,
            'conclusion' => $validated['conclusion'] ?? null,
            'site_representative' => $validated['site_representative'] ?? null,
            'site_representative_title' => $validated['site_representative_title'] ?? null,
        ]);

        $inspection->inspectors()->sync($validated['inspectors']);

        if ($request->wantsJson()) {
            return response()->json($inspection);
        }

        return redirect()->route('inspections.show', $id)->with('success', 'Inspection mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inspection = Inspection::findOrFail($id);
        $inspection->delete();

        if (request()->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('inspections.index')->with('success', 'Inspection supprimée avec succès.');
    }

    /**
     * Approve the specified resource.
     */
    public function approve(string $id)
    {
        $inspection = Inspection::findOrFail($id);

        if ($inspection->status !== 'Brouillon') {
            return abort(403, 'Seules les missions en Brouillon peuvent être approuvées.');
        }

        $inspection->update(['status' => 'Approuvée']);

        if (request()->wantsJson()) {
            return response()->json($inspection);
        }

        return redirect()->route('inspections.show', $id)->with('success', 'La mission a été approuvée avec succès.');
    }
}
