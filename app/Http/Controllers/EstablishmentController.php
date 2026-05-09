<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use Illuminate\Http\Request;

class EstablishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $establishments = Establishment::orderBy('name')->get();

        if (request()->wantsJson()) {
            return response()->json($establishments);
        }

        return view('establishments.index', compact('establishments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('establishments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'contact_title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'rpr_name' => 'nullable|string|max:255',
            'rpr_phone' => 'nullable|string|max:255',
            'rpr_email' => 'nullable|email|max:255',
            'rpr_accreditation' => 'nullable|string|max:255',
        ]);

        $establishment = Establishment::create($validated);

        if ($request->wantsJson()) {
            return response()->json($establishment, 201);
        }

        return redirect()->route('establishments.index')->with('success', 'Établissement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $establishment = Establishment::with(['equipment', 'inspections', 'radioactiveSources', 'usageAuthorizations'])->findOrFail($id);
        $previousEstablishment = Establishment::where('id', '<', $establishment->id)
            ->orderByDesc('id')
            ->first();
        $nextEstablishment = Establishment::where('id', '>', $establishment->id)
            ->orderBy('id')
            ->first();

        if (request()->wantsJson()) {
            return response()->json($establishment);
        }

        return view('establishments.show', compact('establishment', 'previousEstablishment', 'nextEstablishment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $establishment = Establishment::findOrFail($id);
        return view('establishments.edit', compact('establishment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $establishment = Establishment::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'contact_title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'rpr_name' => 'nullable|string|max:255',
            'rpr_phone' => 'nullable|string|max:255',
            'rpr_email' => 'nullable|email|max:255',
            'rpr_accreditation' => 'nullable|string|max:255',
        ]);

        $establishment->update($validated);

        if ($request->wantsJson()) {
            return response()->json($establishment);
        }

        return redirect()->route('establishments.show', $id)->with('success', 'Établissement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $establishment = Establishment::findOrFail($id);
        $establishment->delete();

        if (request()->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('establishments.index')->with('success', 'Établissement supprimé avec succès.');
    }
}
