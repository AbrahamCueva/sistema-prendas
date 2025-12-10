<?php

namespace App\Http\Controllers;

use App\Models\Garment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. CONTEO DE LOTES (Registros en la tabla Garment)
        $totalGarments = Garment::count();
        $pendingGarments = Garment::where('status', 'pendiente')->count();
        $deliveredGarments = Garment::where('status', 'entregado')->count();

        // La condición de auditoría urgente es correcta si usas 'audit_level'
        $urgentGarments = Garment::where('audit_level', 'urgente')->count();

        // 2. DATOS PARA GRÁFICOS (Ejemplo para Líneas de Costura)
        $lineData = Garment::select('stitching_lines.name', DB::raw('count(*) as count'))
            ->join('stitching_lines', 'garments.stitching_line_id', '=', 'stitching_lines.id')
            ->groupBy('stitching_lines.name')
            ->orderByDesc('count')
            ->get();

        $lineLabels = $lineData->pluck('name')->toArray();
        $lineCounts = $lineData->pluck('count')->toArray();

        // 3. DATOS PARA GRÁFICOS (Ejemplo para Motivos de Arreglo)
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
            'lineLabels' => $lineLabels,
            'lineCounts' => $lineCounts,
            'motiveLabels' => $motiveLabels,
            'motiveCounts' => $motiveCounts,
        ]);
    }
}
