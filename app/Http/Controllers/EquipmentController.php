<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Establishment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Equipment::with('establishment');

        if ($request->has('establishment_id')) {
            $query->where('establishment_id', $request->establishment_id);
        }

        $equipment = $query->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($equipment);
        }

        return view('equipment.index', compact('equipment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $establishments = Establishment::all();
        $regulatoryPreviewMap = Equipment::previewSequencesByCategoryAndYear();
        return view('equipment.create', compact('establishments', 'regulatoryPreviewMap'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'regulatory_number' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'installation_date' => 'nullable|date',
            'status' => 'required|string',
            'voltage_max' => 'nullable|string|max:255',
            'intensity_max' => 'nullable|string|max:255',
            'use_case' => 'nullable|string|max:255',
            'filtration' => 'nullable|string|max:255',
        ]);

        $establishment = Establishment::findOrFail($validated['establishment_id']);
        $validated['regulatory_number'] = Equipment::generateRegulatoryNumberForEstablishment(
            $establishment,
            $validated['installation_date'] ?? null
        );

        $item = Equipment::create($validated);

        if ($request->wantsJson()) {
            return response()->json($item, 201);
        }

        return redirect()->route('equipment.index')->with('success', 'Équipement ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        $equipment->load('establishment');
        $previousEquipment = Equipment::where('id', '<', $equipment->id)
            ->orderByDesc('id')
            ->first();
        $nextEquipment = Equipment::where('id', '>', $equipment->id)
            ->orderBy('id')
            ->first();

        if (request()->wantsJson()) {
            return response()->json($equipment);
        }

        return view('equipment.show', compact('equipment', 'previousEquipment', 'nextEquipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        $establishments = Establishment::all();
        return view('equipment.edit', compact('equipment', 'establishments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'regulatory_number' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'installation_date' => 'nullable|date',
            'status' => 'required|string',
            'voltage_max' => 'nullable|string|max:255',
            'intensity_max' => 'nullable|string|max:255',
            'use_case' => 'nullable|string|max:255',
            'filtration' => 'nullable|string|max:255',
        ]);

        $equipment->update($validated);

        if ($request->wantsJson()) {
            return response()->json($equipment);
        }

        return redirect()->route('equipment.index')->with('success', 'Équipement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Deleted successfully']);
        }

        return redirect()->route('equipment.index')->with('success', 'Équipement supprimé.');
    }
}
