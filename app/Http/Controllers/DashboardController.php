<?php

namespace App\Http\Controllers;

use App\Models\Garment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ----------------------------------------------------
        // I. Métricas basadas en CANTIDAD DE PRENDAS (Stock)
        // ----------------------------------------------------
        
        // Cantidad total de prendas ingresadas (quantity_in)
        $totalGarmentsIn = Garment::sum('quantity_in');

        // Cantidad de prendas que ya se entregaron (status 'entregado')
        $deliveredGarmentsQuantity = Garment::where('status', 'entregado')->sum('quantity_in');

        // Cantidad de prendas PENDIENTES (status 'pendiente' o 'en_proceso')
        // Asumo que 'pendiente' y 'en_proceso' son stock que aún está en tus manos.
        $pendingGarmentsQuantity = Garment::whereIn('status', ['pendiente', 'en_proceso'])->sum('quantity_in');
        
        // Si tienes el campo quantity_out, podrías usar la cantidad auditada:
        // $auditedGarmentsQuantity = Garment::sum('quantity_out'); 
        
        // Cantidad de prendas marcadas para Auditoría (is_audit = true)
        $auditedGarmentsQuantity = Garment::where('is_audit', true)->sum('quantity_in');


        // ----------------------------------------------------
        // II. Cálculos de Porcentajes
        // ----------------------------------------------------

        // Tasa de Entrega (Prendas entregadas / Prendas totales ingresadas)
        $deliveryRate = $totalGarmentsIn > 0
            ? number_format(($deliveredGarmentsQuantity / $totalGarmentsIn) * 100, 1)
            : 0;

        // Tasa de Pendientes (Prendas pendientes / Prendas totales ingresadas)
        $pendingRate = $totalGarmentsIn > 0
            ? number_format(($pendingGarmentsQuantity / $totalGarmentsIn) * 100, 1)
            : 0;

        // Tasa de Auditoría/Defecto (Prendas en auditoría / Prendas totales ingresadas)
        $auditRate = $totalGarmentsIn > 0
            ? number_format(($auditedGarmentsQuantity / $totalGarmentsIn) * 100, 1)
            : 0;
            
        // ----------------------------------------------------
        // III. Métricas por Lote (Lotes Urgentes y Pendientes)
        // ----------------------------------------------------
        
        $totalLots = Garment::count(); // El conteo de lotes sigue siendo útil
        $urgentGarmentsLots = Garment::where('audit_level', 'urgente')->count();

        // Lotes Pendientes más antiguos (para la tabla de abajo)
        $latestPendingGarments = Garment::with(['client', 'motive', 'stitchingLine']) // Agregamos stitchingLine si es útil
            ->where('status', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();
            
        // ----------------------------------------------------
        // IV. Datos para Gráficas (Mantenemos el conteo por Lotes, ya que las Gráficas lo reflejan bien)
        // ----------------------------------------------------
        
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
            // Nuevas métricas clave por cantidad de prendas
            'totalGarmentsIn' => $totalGarmentsIn,
            'deliveredGarmentsQuantity' => $deliveredGarmentsQuantity,
            'pendingGarmentsQuantity' => $pendingGarmentsQuantity,
            'auditedGarmentsQuantity' => $auditedGarmentsQuantity,
            
            // Lotes (para conteo simple y urgencia)
            'totalLots' => $totalLots,
            'urgentGarmentsLots' => $urgentGarmentsLots,

            // Tasas (Porcentajes)
            'deliveryRate' => $deliveryRate,
            'pendingRate' => $pendingRate, // Ahora es una tasa más clara
            'auditRate' => $auditRate, // Nueva tasa de auditoría/defecto

            // Datos para listas y gráficos
            'latestPendingGarments' => $latestPendingGarments,
            'lineLabels' => $lineLabels,
            'lineCounts' => $lineCounts,
            'motiveLabels' => $motiveLabels,
            'motiveCounts' => $motiveCounts,
            
            // Eliminamos 'inspectionPendingGarments' y 'rejectionRate' antiguos, reemplazados por el nuevo enfoque
        ]);
    }
}