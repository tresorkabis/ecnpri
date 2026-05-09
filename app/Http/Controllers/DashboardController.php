<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\Inspection;
use App\Models\Equipment;
use App\Models\RadioactiveSource;
use App\Models\Inspector;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'establishments_count' => Establishment::count(),
            'inspections_count' => Inspection::count(),
            'equipment_count' => Equipment::where('status', 'Active')->count(),
            'sources_count' => RadioactiveSource::count(),
            'inspectors_count' => Inspector::count(),
        ];

        // Répartition des missions par statut
        $inspection_stats = Inspection::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $recent_inspections = Inspection::with('establishment')
            ->orderBy('start_date', 'desc')
            ->take(5)
            ->get();

        $upcoming_inspections = Inspection::with('establishment')
            ->where('start_date', '>=', now()->toDateString())
            ->whereIn('status', ['Brouillon', 'Approuvée'])
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        $recent_establishments = Establishment::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats', 
            'recent_inspections', 
            'upcoming_inspections', 
            'recent_establishments',
            'inspection_stats'
        ));
    }
}
