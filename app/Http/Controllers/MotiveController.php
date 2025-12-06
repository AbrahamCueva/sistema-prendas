<?php

namespace App\Http\Controllers;

use App\Models\Motive;
use Illuminate\Http\Request;

class MotiveController extends Controller
{
    private $motiveTypes = ['costura' => 'Costura', 'bordado' => 'Bordado', 'estampado' => 'Estampado', 'general' => 'General'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $motives = Motive::orderBy('name')->paginate(10);
        $motiveTypes = $this->motiveTypes;

        return view('motives.index', compact('motives', 'motiveTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $motiveTypes = $this->motiveTypes;

        return view('motives.create', compact('motiveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:'.implode(',', array_keys($this->motiveTypes)),
        ]);

        Motive::create($request->only(['name', 'type']));

        return redirect()->route('motives.index')
            ->with('success', 'Motivo de arreglo registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Motive $motive)
    {
        $motiveTypes = $this->motiveTypes;

        return view('motives.edit', compact('motive', 'motiveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Motive $motive)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:'.implode(',', array_keys($this->motiveTypes)),
        ]);

        $motive->update($request->only(['name', 'type']));

        return redirect()->route('motives.index')
            ->with('success', 'Motivo de arreglo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Motive $motive)
    {
        if ($motive->garments()->count() > 0) {
            return redirect()->route('motives.index')
                ->with('error', 'No se puede eliminar el motivo porque tiene prendas asociadas.');
        }

        $motive->delete();

        return redirect()->route('motives.index')
            ->with('success', 'Motivo de arreglo eliminado exitosamente.');
    }
}
