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
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class GarmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::orderBy('name')->get();
        $query = Garment::with(['client', 'stitchingLine', 'motive', 'registeredByUser', 'deliveredByUser']);
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
        $query->orderByRaw('quantity_in - quantity_out DESC')
            ->orderBy('delivery_in_date', 'desc');
        $garments = $query->paginate(15);
        $garments->appends($request->query()); // Mantiene los filtros al paginar

        return view('garments.index', compact('garments', 'clients'));
    }

    public function export(Request $request)
    {
        $query = Garment::query();
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
        $query->orderByRaw('quantity_in - quantity_out DESC')
            ->orderBy('delivery_in_date', 'desc');
        $filename = 'lotes_prendas_'.Carbon::now()->format('Ymd_His').'.xlsx';

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
            'pv' => 'required|string|size:5',
            'color' => 'required|string|max:100',
            'sizes' => 'required|string|max:10',
            'quantity_in' => 'required|integer|min:1',
            'delivered_by' => 'required|string|max:255',
            'audit_level' => 'required|in:normal,urgente',
            'defect_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $photoPath = null;
        if ($request->hasFile('defect_photo')) {
            $photoPath = $request->file('defect_photo')->store('defects', 'public');
        }
        Garment::create([
            'client_id' => $request->client_id,
            'stitching_line_id' => $request->stitching_line_id,
            'motive_id' => $request->motive_id,
            'pv' => $request->pv,
            'color' => $request->color,
            'sizes' => $request->sizes,
            'quantity_in' => $request->quantity_in, // USAMOS quantity_in
            'quantity_out' => 0, // Inicia en 0
            'delivered_by' => $request->delivered_by,
            'audit_level' => $request->audit_level,
            'defect_photo_path' => $photoPath,
            'delivery_in_date' => Carbon::now(),
            'registered_by_user_id' => Auth::id(),
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

    public function showAddStockForm(Garment $garment)
    {
        // $motives = Motive::orderBy('name')->get();
        return view('garments.add-stock', compact('garment'));
    }

    /**
     * Procesa la solicitud para incrementar la cantidad del lote.
     */
    public function processAddStock(Request $request, Garment $garment)
    {
        $request->validate([
            'new_quantity' => 'required|integer|min:1',
            'motives_id' => 'nullable|exists:motives,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $newQuantity = (int) $request->new_quantity;
        $totalOldQuantity = $garment->quantity_in;

        DB::transaction(function () use ($garment, $newQuantity, $request, $totalOldQuantity) {
            $garment->quantity_in += $newQuantity;
            if ($request->motives_id) {
                $garment->motive_id = $request->motives_id;
            }
            $garment->save();
            activity()
                ->performedOn($garment)
                ->causedBy(auth()->user())
                ->withProperty('previous_quantity_in', $totalOldQuantity)
                ->withProperty('added_quantity', $newQuantity)
                ->withProperty('notes', $request->notes)
                ->log('Stock añadido al lote');
        });
        return redirect()->route('garments.index')
            ->with('success', "Se han añadido {$newQuantity} unidades al Lote PV {$garment->pv}. Cantidad total de entrada actual: {$garment->fresh()->quantity_in}.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Garment $garment)
    {
        if ($garment->quantity_in == $garment->quantity_out) {
            return redirect()->route('garments.show', $garment)
                ->with('error', 'Este lote ya ha sido entregado en su totalidad y no se puede modificar.');
        }
        $garment->load(['client', 'stitchingLine', 'motive']);

        return view('garments.deliver', compact('garment'));
    }

    public function deliver(Request $request, Garment $garment)
    {
        if ($garment->quantity_in == $garment->quantity_out) {
            return redirect()->route('garments.index')
                ->with('error', 'El lote ya fue marcado como entregado totalmente.');
        }
        $pendingQuantity = $garment->quantity_in - $garment->quantity_out;
        $request->validate([
            'received_by' => 'required|string|max:255',
            'quantity_delivered' => 'required|integer|min:1|max:'.$pendingQuantity,
        ]);
        $quantityToDeliver = $request->quantity_delivered;
        $newQuantityOut = $garment->quantity_out + $quantityToDeliver;
        $quantityRemaining = $garment->quantity_in - $newQuantityOut;
        $updateData = [
            'quantity_out' => $newQuantityOut,
            'received_by' => $request->received_by,
            'delivery_out_date' => Carbon::now(),
            'delivered_by_user_id' => Auth::id(),
        ];
        if ($quantityRemaining <= 0) {
            $updateData['status'] = 'entregado';
        }
        $garment->update($updateData);
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
        if ($garment->quantity_in !== $garment->quantity_out) {
            return redirect()->route('garments.index')->with(
                'error',
                "No se puede eliminar el lote **PV {$garment->pv}** porque su estado es 'pendiente' (quedan {$garment->getQuantityPendingAttribute()} prendas). Solo se permiten eliminar lotes ya entregados totalmente."
            );
        }
        $garment->delete();

        return redirect()->route('garments.index')->with(
            'success',
            "El lote **PV {$garment->pv}** ha sido eliminado permanentemente."
        );
    }
}
