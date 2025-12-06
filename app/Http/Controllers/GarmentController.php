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
            ->orderByRaw("FIELD(status, 'pendiente', 'entregado')") // Pendiente primero
            ->orderBy('delivery_in_date', 'desc')
            ->paginate(15);

        return view('garments.index', compact('garments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Se necesitan los datos de las tablas maestras
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
            'pv' => 'required|string|size:5|unique:garments,pv',
            'color' => 'required|string|max:100',
            'delivered_by' => 'required|string|max:255',
            'is_audit' => 'nullable|boolean',
        ]);

        Garment::create([
            'client_id' => $request->client_id,
            'stitching_line_id' => $request->stitching_line_id,
            'motive_id' => $request->motive_id,
            'pv' => $request->pv,
            'color' => $request->color,
            'delivered_by' => $request->delivered_by,
            'is_audit' => $request->has('is_audit'),
            'delivery_in_date' => Carbon::now(), // Fecha y hora de registro de entrada
            'registered_by_user_id' => Auth::id(), // ID del usuario autenticado que registra
            'status' => 'pendiente',
        ]);

        return redirect()->route('garments.index')
            ->with('success', 'Prenda registrada con éxito. ¡Pendiente de entrega!');
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
        //
    }
}
