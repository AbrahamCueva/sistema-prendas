<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::query()
        // Filtrar por texto de búsqueda
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', '%'.$search.'%')
                        ->orWhere('subject_type', 'like', '%'.$search.'%')
                      // Asume que 'causer' es la relación que trae el usuario
                        ->orWhereHas('causer', function ($q) use ($search) {
                            $q->where('name', 'like', '%'.$search.'%');
                        });
                });
            })
        // Filtrar por evento (created, updated, deleted)
            ->when($request->event, function ($query, $event) {
                $query->where('event', $event);
            })
        // Filtrar por fecha de inicio
            ->when($request->start_date, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
        // Filtrar por fecha de fin
            ->when($request->end_date, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->latest() // Ordenar por más reciente primero
            ->paginate(15); // Paginación por 15 elementos

        return view('audit.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        // El Activity model ya está inyectado por Laravel
        // Cargamos la relación del causer (usuario)
        $activity->load('causer');

        return view('audit.show', compact('activity'));
    }

    public function report(Activity $activity)
    {
        // 1. Carga los datos de la actividad
        // Esto ya lo tienes hecho gracias al Route Model Binding (Activity $activity)

        // 2. Carga la vista que contendrá el diseño del PDF (ej: resources/views/pdf/audit_report.blade.php)
        $pdf = Pdf::loadView('pdf.audit_report', compact('activity'));

        // 3. Descarga el PDF
        $filename = 'auditoria_'.$activity->id.'_'.$activity->created_at->format('Ymd_His').'.pdf';

        return $pdf->download($filename);
    }
}
