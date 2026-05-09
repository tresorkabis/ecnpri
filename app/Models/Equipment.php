<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'establishment_id', 'name', 'model', 'serial_number', 'regulatory_number', 
        'manufacturer', 'installation_date', 'status',
        'voltage_max', 'intensity_max', 'use_case', 'filtration'
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public static function buildRegulatoryNumber(string $categoryCode, int $year, int $sequence): string
    {
        return sprintf('CNPRI-EQ-%s-%d-%03d', $categoryCode, $year, $sequence);
    }

    public static function nextSequenceFor(string $categoryCode, int $year): int
    {
        $prefix = sprintf('CNPRI-EQ-%s-%d-', $categoryCode, $year);

        $maxSequence = static::query()
            ->where('regulatory_number', 'like', $prefix . '%')
            ->pluck('regulatory_number')
            ->map(function (?string $regulatoryNumber) {
                if ($regulatoryNumber && preg_match('/-(\d+)$/', $regulatoryNumber, $matches)) {
                    return (int) $matches[1];
                }

                return 0;
            })
            ->max();

        return ((int) $maxSequence) + 1;
    }

    public static function generateRegulatoryNumberForEstablishment(Establishment $establishment, ?string $installationDate = null): string
    {
        $year = $installationDate ? (int) date('Y', strtotime($installationDate)) : (int) now()->format('Y');
        $categoryCode = UsageAuthorization::categoryCode($establishment->category);
        $sequence = static::nextSequenceFor($categoryCode, $year);

        return static::buildRegulatoryNumber($categoryCode, $year, $sequence);
    }

    public static function previewSequencesByCategoryAndYear(): array
    {
        return static::query()
            ->pluck('regulatory_number')
            ->reduce(function (array $carry, ?string $regulatoryNumber) {
                if (!$regulatoryNumber || !preg_match('#^CNPRI-EQ-([A-Z]{3})-(\d{4})-(\d+)$#', $regulatoryNumber, $matches)) {
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
