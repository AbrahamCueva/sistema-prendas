<?php

namespace App\Http\Controllers;

use App\Exports\GarmentsExport;
use App\Models\Client;
use App\Models\Garment;
use App\Models\Motive;
use App\Models\StitchingLine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class GarmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Obtener todos los clientes para el selector de filtro
        $clients = Client::orderBy('name')->get();

        // 2. Iniciar la consulta base
        $query = Garment::with(['client', 'stitchingLine', 'motive', 'registeredByUser', 'deliveredByUser']);

        // 3. Aplicar Filtros
        if ($request->filled('search_pv')) {
            $query->where('pv', 'like', '%'.$request->search_pv.'%');
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // MODIFICADO: El estado 'entregado' ahora significa que quantity_in == quantity_out
        if ($request->filled('status') && $request->status !== 'todos') {
            if ($request->status === 'entregado') {
                $query->whereRaw('quantity_in = quantity_out');
            } elseif ($request->status === 'pendiente') {
                $query->whereRaw('quantity_in > quantity_out');
            }
        }

        if ($request->filled('date_from')) {
            // Se asume que 'date_from' es el inicio del día
            $query->whereDate('delivery_in_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            // Se asume que 'date_to' es el final del día
            $query->whereDate('delivery_in_date', '<=', $request->date_to);
        }

        // 4. Ordenación (Pendientes primero, luego Entregados, por fecha descendente)
        // MODIFICADO: Ordenar por cantidad pendiente
        $query->orderByRaw('quantity_in - quantity_out DESC')
            ->orderBy('delivery_in_date', 'desc');

        // 5. Paginación
        $garments = $query->paginate(15);
        $garments->appends($request->query()); // Mantiene los filtros al paginar

        return view('garments.index', compact('garments', 'clients'));
    }

    public function export(Request $request)
    {
        // 1. Iniciar la consulta base
        $query = Garment::query();

        // 2. Aplicar Filtros (la misma lógica que en el método index)
        if ($request->filled('search_pv')) {
            $query->where('pv', 'like', '%'.$request->search_pv.'%');
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('status') && $request->status !== 'todos') {
            if ($request->status === 'entregado') {
                $query->whereRaw('quantity_in = quantity_out');
            } elseif ($request->status === 'pendiente') {
                $query->whereRaw('quantity_in > quantity_out');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('delivery_in_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('delivery_in_date', '<=', $request->date_to);
        }

        // 3. Ordenación (Opcional, pero recomendado para mantener la coherencia)
        $query->orderByRaw('quantity_in - quantity_out DESC')
            ->orderBy('delivery_in_date', 'desc');

        // 4. Descargar el archivo
        $filename = 'lotes_prendas_'.Carbon::now()->format('Ymd_His').'.xlsx';

        // Se pasa la consulta filtrada a la clase exportadora
        return Excel::download(new GarmentsExport($query), $filename);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $lines = StitchingLine::orderBy('name')->get();
        $motives = Motive::orderBy('name')->get();

        // Generar un PV aleatorio de 5 dígitos (simple)
        $randomPV = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

        return view('garments.create', compact('clients', 'lines', 'motives', 'randomPV'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'stitching_line_id' => 'required|exists:stitching_lines,id',
            'motive_id' => 'required|exists:motives,id',
            'pv' => 'required|string|size:5', // PV ya no es unique
            'color' => 'required|string|max:100',
            'size' => 'required|string|max:10', // NUEVO: Talla
            'quantity_in' => 'required|integer|min:1', // MODIFICADO: Cantidad de entrada
            'delivered_by' => 'required|string|max:255',
            'audit_level' => 'required|in:normal,urgente', // MODIFICADO: Nivel de auditoría
            'defect_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('defect_photo')) {
            // Almacenar la foto en la carpeta 'public/defects' y guardar la ruta
            $photoPath = $request->file('defect_photo')->store('defects', 'public');
        }

        Garment::create([
            'client_id' => $request->client_id,
            'stitching_line_id' => $request->stitching_line_id,
            'motive_id' => $request->motive_id,
            'pv' => $request->pv,
            'color' => $request->color,
            'size' => $request->size,
            'quantity_in' => $request->quantity_in, // USAMOS quantity_in
            'quantity_out' => 0, // Inicia en 0
            'delivered_by' => $request->delivered_by,
            'audit_level' => $request->audit_level,
            'defect_photo_path' => $photoPath,
            'delivery_in_date' => Carbon::now(),
            'registered_by_user_id' => Auth::id(),
            // Ya no usamos 'status' literal, se calcula. Se puede mantener para compatibilidad si la vista lo requiere, pero quantity_in > quantity_out es la nueva 'pendiente'
            // 'status' => 'pendiente',
        ]);

        return redirect()->route('garments.index')
            ->with('success', 'Lote de prendas PV '.$request->pv.' registrado con éxito. ¡Pendiente de entrega!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Garment $garment)
    {
        $garment->load(['client', 'stitchingLine', 'motive', 'registeredByUser', 'deliveredByUser']);

        return view('garments.show', compact('garment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Garment $garment)
    {
        // El lote ya se considera "entregado" si no hay nada pendiente (quantity_in == quantity_out)
        if ($garment->quantity_in == $garment->quantity_out) {
            return redirect()->route('garments.show', $garment)
                ->with('error', 'Este lote ya ha sido entregado en su totalidad y no se puede modificar.');
        }

        // Simplemente redirigimos a un formulario de confirmación de entrega
        $garment->load(['client', 'stitchingLine', 'motive']);

        return view('garments.deliver', compact('garment'));
    }

    // En App\Http\Controllers\GarmentController.php

    public function deliver(Request $request, Garment $garment)
    {
        // 1. Verificación inicial de estado (aunque la validación de cantidad ya lo hace)
        if ($garment->quantity_in == $garment->quantity_out) {
            return redirect()->route('garments.index')
                ->with('error', 'El lote ya fue marcado como entregado totalmente.');
        }

        $pendingQuantity = $garment->quantity_in - $garment->quantity_out;

        // 2. Validación de la solicitud
        $request->validate([
            'received_by' => 'required|string|max:255',
            // La cantidad a entregar no puede superar lo pendiente
            'quantity_delivered' => 'required|integer|min:1|max:'.$pendingQuantity,
        ]);

        $quantityToDeliver = $request->quantity_delivered;
        $newQuantityOut = $garment->quantity_out + $quantityToDeliver;
        $quantityRemaining = $garment->quantity_in - $newQuantityOut;

        // 3. Preparar los datos de actualización
        $updateData = [
            'quantity_out' => $newQuantityOut, // Sumar la nueva entrega
            'received_by' => $request->received_by,
            'delivery_out_date' => Carbon::now(), // Siempre se actualiza con la última salida
            'delivered_by_user_id' => Auth::id(),
        ];

        // **MODIFICACIÓN CLAVE:** Actualizar el campo 'status' si la entrega es total
        if ($quantityRemaining <= 0) {
            $updateData['status'] = 'entregado';
        }
        // Si quedan pendientes, el status permanece como 'pendiente' (asumiendo que se guardó así en 'store')

        // 4. Ejecutar la actualización en la base de datos
        $garment->update($updateData);

        // 5. Mensaje de éxito
        if ($quantityRemaining > 0) {
            $message = "Entrega parcial registrada. Se entregaron **{$quantityToDeliver}** prendas (PV {$garment->pv}). Quedan **{$quantityRemaining}** prendas **PENDIENTES** en este lote.";
        } else {
            $message = "Entrega total del lote PV **{$garment->pv}** ({$garment->quantity_in} prendas) marcada como entregada exitosamente.";
        }

        return redirect()->route('garments.index')->with('success', $message);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Garment $garment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Garment $garment)
    {
        // Validación de Regla de Negocio:
        // Se puede eliminar si está totalmente entregado.
        if ($garment->quantity_in !== $garment->quantity_out) {
            return redirect()->route('garments.index')->with(
                'error',
                "No se puede eliminar el lote **PV {$garment->pv}** porque su estado es 'pendiente' (quedan {$garment->getQuantityPendingAttribute()} prendas). Solo se permiten eliminar lotes ya entregados totalmente."
            );
        }

        // Si la validación pasa, procede a eliminar
        $garment->delete();

        return redirect()->route('garments.index')->with(
            'success',
            "El lote **PV {$garment->pv}** ha sido eliminado permanentemente."
        );
    }
}
