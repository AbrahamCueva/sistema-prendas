<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Garment;
use App\Models\Motive;
use App\Models\StitchingLine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GarmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $garments = Garment::with(['client', 'stitchingLine', 'motive', 'registeredByUser', 'deliveredByUser'])
            ->orderByRaw("FIELD(status, 'pendiente', 'entregado')")
            ->orderBy('delivery_in_date', 'desc')
            ->paginate(15);

        return view('garments.index', compact('garments'));
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
            'quantity' => 'required|integer|min:1', // NUEVO: Cantidad
            'delivered_by' => 'required|string|max:255',
            'audit_level' => 'required|in:normal,urgente', // MODIFICADO: Nivel de auditoría
        ]);

        Garment::create([
            'client_id' => $request->client_id,
            'stitching_line_id' => $request->stitching_line_id,
            'motive_id' => $request->motive_id,
            'pv' => $request->pv,
            'color' => $request->color,
            'size' => $request->size, // Nuevo campo
            'quantity' => $request->quantity, // Nuevo campo
            'delivered_by' => $request->delivered_by,
            'audit_level' => $request->audit_level, // Nuevo campo
            'delivery_in_date' => Carbon::now(),
            'registered_by_user_id' => Auth::id(),
            'status' => 'pendiente',
        ]);

        return redirect()->route('garments.index')
            ->with('success', 'Lote de prendas PV ' . $request->pv . ' registrado con éxito. ¡Pendiente de entrega!');
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
        if ($garment->status === 'entregado') {
            return redirect()->route('garments.show', $garment)
                ->with('error', 'Esta prenda ya fue entregada y no se puede modificar su estado.');
        }

        // Simplemente redirigimos a un formulario de confirmación de entrega
        $garment->load(['client', 'stitchingLine', 'motive']);

        return view('garments.deliver', compact('garment'));
    }

    public function deliver(Request $request, Garment $garment)
    {
        if ($garment->status === 'entregado') {
            return redirect()->route('garments.index')
                ->with('error', 'La prenda ya fue marcada como entregada previamente.');
        }

        $request->validate([
            'received_by' => 'required|string|max:255',
        ]);

        $garment->update([
            'received_by' => $request->received_by, // Persona que recibe la prenda de vuelta
            'delivery_out_date' => Carbon::now(), // Fecha y hora de devolución
            'delivered_by_user_id' => Auth::id(), // Usuario que registra la salida
            'status' => 'entregado',
        ]);

        return redirect()->route('garments.index')
            ->with('success', "Prenda PV **{$garment->pv}** marcada como entregada exitosamente.");
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
        // Solo se puede eliminar si ya ha sido entregada.
        if ($garment->status !== 'entregado') {
            return redirect()->route('garments.index')->with(
                'error',
                "No se puede eliminar la prenda **PV {$garment->pv}** porque su estado es 'pendiente'. Solo se permiten eliminar lotes ya entregados."
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
