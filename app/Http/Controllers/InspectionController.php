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
    public function index()
    {
        $inspections = Inspection::with(['establishment', 'inspectors'])
            ->orderBy('start_date', 'desc')
            ->get();

        if (request()->wantsJson()) {
            return response()->json($inspections);
        }

        return view('inspections.index', compact('inspections'));
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
            'type' => 'required|string|in:réglementaire,inopiné',
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
            'authorized_by' => $validated['authorized_by'] ?? null,
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
            'type' => 'required|string|in:réglementaire,inopiné',
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
            'authorized_by' => $validated['authorized_by'] ?? null,
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
