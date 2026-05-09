<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class UsageAuthorization extends Model
{
    protected $fillable = [
        'establishment_id',
        'reference_number',
        'authorization_type',
        'scope',
        'issuing_authority',
        'issued_at',
        'expires_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public static function authorizationTypes(): array
    {
        return [
            'Radiodiagnostic médical',
            'Radiothérapie',
            'Médecine nucléaire',
            'Diagnostics dentaires',
            'Diagnostics vétérinaires',
            'Radio industrielle',
            'Utilisation des appareils importés',
            'Utilisation des jauges',
            'Utilisation des sources',
            'Utilisation diagraphie',
            'Expérimentale',
            'Démonstration',
            'Recherche',
        ];
    }

    public static function scopes(): array
    {
        return ['Sources', 'Équipements', 'Sources et Équipements'];
    }

    public static function statuses(): array
    {
        return ['Valide', 'Expirée', 'Suspendue', 'En attente'];
    }

    public static function categoryCode(?string $category): string
    {
        return match ($category) {
            'Médical' => 'MED',
            'Industriel' => 'IND',
            'Mines' => 'MIN',
            'Recherche' => 'RES',
            default => 'AUT',
        };
    }

    public static function buildReferenceNumber(string $categoryCode, int $year, int $sequence): string
    {
        return sprintf('CNPRI/AUT/%s/%d/%03d', $categoryCode, $year, $sequence);
    }

    public static function nextSequenceFor(string $categoryCode, int $year): int
    {
        $prefix = sprintf('CNPRI/AUT/%s/%d/', $categoryCode, $year);

        $maxSequence = static::query()
            ->where('reference_number', 'like', $prefix . '%')
            ->pluck('reference_number')
            ->map(function (string $referenceNumber) {
                if (preg_match('/\/(\d+)$/', $referenceNumber, $matches)) {
                    return (int) $matches[1];
                }

                return 0;
            })
            ->max();

        return ((int) $maxSequence) + 1;
    }

    public static function generateReferenceNumberForEstablishment(Establishment $establishment, ?string $issuedAt = null): string
    {
        $year = $issuedAt ? (int) date('Y', strtotime($issuedAt)) : (int) now()->format('Y');
        $categoryCode = static::categoryCode($establishment->category);
        $sequence = static::nextSequenceFor($categoryCode, $year);

        return static::buildReferenceNumber($categoryCode, $year, $sequence);
    }

    public static function previewSequencesByCategoryAndYear(): array
    {
        return static::query()
            ->pluck('reference_number')
            ->reduce(function (array $carry, string $referenceNumber) {
                if (!preg_match('#^CNPRI/AUT/([A-Z]{3})/(\d{4})/(\d+)$#', $referenceNumber, $matches)) {
                    return $carry;
                }

                $categoryCode = $matches[1];
                $year = $matches[2];
                $sequence = (int) $matches[3];

                $carry[$categoryCode][$year] = max($carry[$categoryCode][$year] ?? 0, $sequence);

                return $carry;
            }, []);
    }
}
