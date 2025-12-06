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
        $urgentGarments = Garment::where('status', 'pendiente')->where('is_audit', true)->count();

        $linesData = Garment::select('stitching_lines.name', DB::raw('count(*) as total'))
            ->join('stitching_lines', 'garments.stitching_line_id', '=', 'stitching_lines.id')
            ->groupBy('stitching_lines.name')
            ->orderBy('total', 'desc')
            ->get();

        $lineLabels = $linesData->pluck('name')->toArray();
        $lineCounts = $linesData->pluck('total')->toArray();

        $motiveData = Garment::select('motives.name', DB::raw('count(*) as total'))
            ->join('motives', 'garments.motive_id', '=', 'motives.id')
            ->groupBy('motives.name')
            ->orderBy('total', 'desc')
            ->get();

        $motiveLabels = $motiveData->pluck('name')->toArray();
        $motiveCounts = $motiveData->pluck('total')->toArray();

        return view('dashboard', compact(
            'totalGarments', 'pendingGarments', 'deliveredGarments', 'urgentGarments',
            'lineLabels', 'lineCounts',
            'motiveLabels', 'motiveCounts' // ¡Nuevos datos para el segundo gráfico!
        ));
    }
}
