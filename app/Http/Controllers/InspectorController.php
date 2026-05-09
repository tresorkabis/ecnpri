<?php

namespace App\Http\Controllers;

use App\Models\Inspector;
use Illuminate\Http\Request;

class InspectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inspectors = Inspector::latest()->get();

        if (request()->wantsJson()) {
            return response()->json($inspectors);
        }

        return view('inspectors.index', compact('inspectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inspectors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:255',
            'employee_id' => 'required|string|unique:inspectors,employee_id',
            'specialization' => 'nullable|string|max:255',
        ]);

        $inspector = Inspector::create($validated);

        if ($request->wantsJson()) {
            return response()->json($inspector, 201);
        }

        return redirect()->route('inspectors.index')->with('success', 'Inspecteur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inspector $inspector)
    {
        $inspector->load('inspections.establishment');
        $previousInspector = Inspector::where('id', '<', $inspector->id)
            ->orderByDesc('id')
            ->first();
        $nextInspector = Inspector::where('id', '>', $inspector->id)
            ->orderBy('id')
            ->first();

        if (request()->wantsJson()) {
            return response()->json($inspector);
        }

        return view('inspectors.show', compact('inspector', 'previousInspector', 'nextInspector'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inspector $inspector)
    {
        return view('inspectors.edit', compact('inspector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inspector $inspector)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:255',
            'employee_id' => 'required|string|unique:inspectors,employee_id,' . $inspector->id,
            'specialization' => 'nullable|string|max:255',
        ]);

        $inspector->update($validated);

        if ($request->wantsJson()) {
            return response()->json($inspector);
        }

        return redirect()->route('inspectors.index')->with('success', 'Inspecteur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inspector $inspector)
    {
        $inspector->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Deleted successfully']);
        }

        return redirect()->route('inspectors.index')->with('success', 'Inspecteur supprimé avec succès.');
    }
}
