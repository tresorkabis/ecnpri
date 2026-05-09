<?php

namespace App\Http\Controllers;

use App\Models\RadioactiveSource;
use App\Models\Establishment;
use Illuminate\Http\Request;

class RadioactiveSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sources = RadioactiveSource::with('establishment')->orderBy('isotope')->get();

        if (request()->wantsJson()) {
            return response()->json($sources);
        }

        return view('radioactive_sources.index', compact('sources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $establishments = Establishment::orderBy('name')->get();
        $regulatoryPreviewMap = RadioactiveSource::previewSequencesByCategoryAndYear();
        return view('radioactive_sources.create', compact('establishments', 'regulatoryPreviewMap'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
            'serial_number' => 'required|string|unique:radioactive_sources,serial_number',
            'regulatory_number' => 'nullable|string|max:255',
            'isotope' => 'required|string|max:50',
            'initial_activity' => 'required|numeric',
            'unit' => 'required|string|max:10',
            'activity_date' => 'required|date',
            'physical_form' => 'required|string',
            'category' => 'nullable|string',
            'status' => 'required|string',
            'location_details' => 'nullable|string',
        ]);

        $establishment = Establishment::findOrFail($validated['establishment_id']);
        $validated['regulatory_number'] = RadioactiveSource::generateRegulatoryNumberForEstablishment(
            $establishment,
            $validated['activity_date'] ?? null
        );

        $source = RadioactiveSource::create($validated);

        if ($request->wantsJson()) {
            return response()->json($source, 201);
        }

        return redirect()->route('radioactive-sources.index')->with('success', 'Source radioactive enregistrée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $source = RadioactiveSource::with('establishment')->findOrFail($id);
        $previousSource = RadioactiveSource::where('id', '<', $source->id)
            ->orderByDesc('id')
            ->first();
        $nextSource = RadioactiveSource::where('id', '>', $source->id)
            ->orderBy('id')
            ->first();

        if (request()->wantsJson()) {
            return response()->json($source);
        }

        return view('radioactive_sources.show', compact('source', 'previousSource', 'nextSource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $source = RadioactiveSource::findOrFail($id);
        $establishments = Establishment::orderBy('name')->get();
        return view('radioactive_sources.edit', compact('source', 'establishments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $source = RadioactiveSource::findOrFail($id);

        $validated = $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
            'serial_number' => 'required|string|unique:radioactive_sources,serial_number,' . $id,
            'regulatory_number' => 'nullable|string|max:255',
            'isotope' => 'required|string|max:50',
            'initial_activity' => 'required|numeric',
            'unit' => 'required|string|max:10',
            'activity_date' => 'required|date',
            'physical_form' => 'required|string',
            'category' => 'nullable|string',
            'status' => 'required|string',
            'location_details' => 'nullable|string',
        ]);

        $source->update($validated);

        if ($request->wantsJson()) {
            return response()->json($source);
        }

        return redirect()->route('radioactive-sources.show', $id)->with('success', 'Source mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $source = RadioactiveSource::findOrFail($id);
        $source->delete();

        if (request()->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('radioactive-sources.index')->with('success', 'Source supprimée avec succès.');
    }
}
