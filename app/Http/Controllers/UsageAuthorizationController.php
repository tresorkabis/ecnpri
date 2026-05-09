<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\UsageAuthorization;
use Illuminate\Http\Request;

class UsageAuthorizationController extends Controller
{
    public function index(Request $request)
    {
        $query = UsageAuthorization::with('establishment')->orderByDesc('issued_at')->orderBy('reference_number');

        if ($request->filled('establishment_id')) {
            $query->where('establishment_id', $request->establishment_id);
        }

        $authorizations = $query->get();

        if ($request->wantsJson()) {
            return response()->json($authorizations);
        }

        return view('usage_authorizations.index', [
            'authorizations' => $authorizations,
        ]);
    }

    public function create(Request $request)
    {
        return view('usage_authorizations.create', [
            'establishments' => Establishment::orderBy('name')->get(),
            'authorizationTypes' => UsageAuthorization::authorizationTypes(),
            'scopes' => UsageAuthorization::scopes(),
            'statuses' => UsageAuthorization::statuses(),
            'selectedEstablishmentId' => $request->query('establishment_id'),
            'referencePreviewMap' => UsageAuthorization::previewSequencesByCategoryAndYear(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateAuthorization($request, false);
        $establishment = Establishment::findOrFail($validated['establishment_id']);
        $validated['reference_number'] = UsageAuthorization::generateReferenceNumberForEstablishment(
            $establishment,
            $validated['issued_at'] ?? null
        );

        $authorization = UsageAuthorization::create($validated);

        if ($request->wantsJson()) {
            return response()->json($authorization, 201);
        }

        return redirect()->route('usage-authorizations.index')
            ->with('success', 'Autorisation enregistrée avec succès.');
    }

    public function show(UsageAuthorization $usageAuthorization)
    {
        $usageAuthorization->load([
            'establishment.equipment',
            'establishment.radioactiveSources',
        ]);
        $previousAuthorization = UsageAuthorization::where('id', '<', $usageAuthorization->id)
            ->orderByDesc('id')
            ->first();
        $nextAuthorization = UsageAuthorization::where('id', '>', $usageAuthorization->id)
            ->orderBy('id')
            ->first();

        if (request()->wantsJson()) {
            return response()->json($usageAuthorization);
        }

        return view('usage_authorizations.show', [
            'authorization' => $usageAuthorization,
            'previousAuthorization' => $previousAuthorization,
            'nextAuthorization' => $nextAuthorization,
        ]);
    }

    public function edit(UsageAuthorization $usageAuthorization)
    {
        return view('usage_authorizations.edit', [
            'authorization' => $usageAuthorization,
            'establishments' => Establishment::orderBy('name')->get(),
            'authorizationTypes' => UsageAuthorization::authorizationTypes(),
            'scopes' => UsageAuthorization::scopes(),
            'statuses' => UsageAuthorization::statuses(),
            'referencePreviewMap' => UsageAuthorization::previewSequencesByCategoryAndYear(),
        ]);
    }

    public function update(Request $request, UsageAuthorization $usageAuthorization)
    {
        $validated = $this->validateAuthorization($request, true);
        $usageAuthorization->update($validated);

        if ($request->wantsJson()) {
            return response()->json($usageAuthorization);
        }

        return redirect()->route('usage-authorizations.show', $usageAuthorization)
            ->with('success', 'Autorisation mise à jour avec succès.');
    }

    public function destroy(UsageAuthorization $usageAuthorization)
    {
        $usageAuthorization->delete();

        if (request()->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('usage-authorizations.index')
            ->with('success', 'Autorisation supprimée avec succès.');
    }

    private function validateAuthorization(Request $request, bool $requiresReferenceNumber): array
    {
        return $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
            'reference_number' => $requiresReferenceNumber ? 'required|string|max:255' : 'nullable|string|max:255',
            'authorization_type' => 'required|string|in:' . implode(',', UsageAuthorization::authorizationTypes()),
            'scope' => 'required|string|in:' . implode(',', UsageAuthorization::scopes()),
            'issuing_authority' => 'nullable|string|max:255',
            'issued_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:issued_at',
            'status' => 'required|string|in:' . implode(',', UsageAuthorization::statuses()),
            'notes' => 'nullable|string',
        ]);
    }
}
