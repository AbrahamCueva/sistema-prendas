<?php

namespace App\Http\Controllers;

use App\Models\StitchingLine;
use Illuminate\Http\Request;

class StitchingLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lines = StitchingLine::orderBy('name')->paginate(10);
        return view('stitching-lines.index', compact('lines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stitching-lines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:stitching_lines,name',
            'is_external_service' => 'nullable|boolean',
        ]);
        StitchingLine::create([
            'name' => $request->name,
            'is_external_service' => $request->has('is_external_service'),
        ]);
        return redirect()->route('stitching-lines.index')
            ->with('success', 'Línea de Costura registrada exitosamente.');
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
    public function edit(StitchingLine $stitchingLine)
    {
        return view('stitching-lines.edit', compact('stitchingLine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StitchingLine $stitchingLine)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:stitching_lines,name,'.$stitchingLine->id,
            'is_external_service' => 'nullable|boolean',
        ]);
        $stitchingLine->update([
            'name' => $request->name,
            'is_external_service' => $request->has('is_external_service'),
        ]);
        return redirect()->route('stitching-lines.index')
            ->with('success', 'Línea de Costura actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StitchingLine $stitchingLine)
    {
        if ($stitchingLine->garments()->count() > 0) {
            return redirect()->route('stitching-lines.index')
                ->with('error', 'No se puede eliminar la línea porque tiene prendas asociadas.');
        }
        $stitchingLine->delete();
        return redirect()->route('stitching-lines.index')
            ->with('success', 'Línea de Costura eliminada exitosamente.');
    }
}
