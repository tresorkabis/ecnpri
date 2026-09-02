<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Programme - {{ $annee }} / S{{ $semestre }}</title>
    <style>
        body { font-family: sans-serif; margin: 40px; color: #333; }
        h1 { font-size: 18pt; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #666; padding: 6px 8px; font-size: 9pt; }
        th { background-color: #eee; font-weight: bold; }
        .signature { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <h1>Proposition du Programme des Inspections — {{ $annee }} / {{ $semestre === 2 ? '2e semestre' : '1er semestre' }}</h1>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Installation</th>
                <th>Localisation</th>
                <th>Inspecteurs</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($flat as $i => $inspection)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $inspection->start_date?->format('d/m/Y') ?? '—' }}</td>
                    <td>{{ $inspection->establishment->name ?? '—' }}</td>
                    <td>{{ $inspection->establishment->address ?? ($inspection->establishment->city ?? '—') }}</td>
                    <td>{{ implode(', ', $inspection->inspectors->pluck('name')->toArray()) }}</td>
                    <td>{{ ucfirst($inspection->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center">Aucune inspection trouvée.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature">
        Fait à {{ config('cnpri.signature_ville') }}, le {{ now()->format('d/m/Y') }}
        <br><br>
        {{ config('cnpri.signature_name') }}
        <br>
        {{ config('cnpri.signature_title') }}
    </div>
</body>
</html>