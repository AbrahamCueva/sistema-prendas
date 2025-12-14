<?php

namespace App\Http\Controllers;

use App\Models\Garment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGarments = Garment::count();
        $pendingGarments = Garment::where('status', 'pendiente')->count();
        $deliveredGarments = Garment::where('status', 'entregado')->count();
        $urgentGarments = Garment::where('audit_level', 'urgente')->count();
        $deliveryRate = $totalGarments > 0
            ? number_format(($deliveredGarments / $totalGarments) * 100, 1)
            : 0;
        $rejectionRate = $totalGarments > 0
            ? number_format(($pendingGarments / $totalGarments) * 100, 1) // Porcentaje que AÚN no se ha completado
            : 0;
        $inspectionPendingGarments = Garment::where('status', 'inspeccion')->count();
        $latestPendingGarments = Garment::with(['client', 'motive'])
            ->where('status', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();
        $lineData = Garment::select('stitching_lines.name', DB::raw('count(*) as count'))
            ->join('stitching_lines', 'garments.stitching_line_id', '=', 'stitching_lines.id')
            ->groupBy('stitching_lines.name')
            ->orderByDesc('count')
            ->get();
        $lineLabels = $lineData->pluck('name')->toArray();
        $lineCounts = $lineData->pluck('count')->toArray();
        $motiveData = Garment::select('motives.name', DB::raw('count(*) as count'))
            ->join('motives', 'garments.motive_id', '=', 'motives.id')
            ->groupBy('motives.name')
            ->orderByDesc('count')
            ->get();
        $motiveLabels = $motiveData->pluck('name')->toArray();
        $motiveCounts = $motiveData->pluck('count')->toArray();
        return view('dashboard', [
            'totalGarments' => $totalGarments,
            'pendingGarments' => $pendingGarments,
            'deliveredGarments' => $deliveredGarments,
            'urgentGarments' => $urgentGarments,
            'deliveryRate' => $deliveryRate,
            'rejectionRate' => $rejectionRate,
            'inspectionPendingGarments' => $inspectionPendingGarments,
            'latestPendingGarments' => $latestPendingGarments,
            'lineLabels' => $lineLabels,
            'lineCounts' => $lineCounts,
            'motiveLabels' => $motiveLabels,
            'motiveCounts' => $motiveCounts,
        ]);
    }
}
