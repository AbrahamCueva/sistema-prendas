<?php

namespace App\Exports;

use App\Models\Garment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class GarmentsExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $query;

    /**
     * Recibe la query ya filtrada desde el controlador.
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Retorna la consulta Eloquent con los filtros aplicados.
     */
    public function query()
    {
        return $this->query->with(['client', 'stitchingLine', 'motive', 'registeredByUser', 'deliveredByUser']);
    }

    /**
     * Define los encabezados de las columnas.
     */
    public function headings(): array
    {
        return [
            'ID Lote',
            'PV',
            'Cliente',
            'Talla',
            'Color',
            // --- MODIFICACIÓN DE CANTIDADES ---
            'Cant. Ingreso',
            'Cant. Entregada',
            'Cant. Pendiente',
            // ----------------------------------
            'Línea de Costura',
            'Motivo de Arreglo',
            'Nivel Auditoría',
            'Fecha Entrada',
            'Estado',
            'Usuario Registro',
            'Fecha Entrega Final', // Mejorar la descripción
            'Usuario Entrega Final', // Mejorar la descripción
            'Recibido Por', // Nuevo campo si lo usas en el modelo
        ];
    }

    /**
     * Mapea cada objeto Garment a una fila de la hoja de cálculo.
     */
    public function map($garment): array
    {
        $quantityPending = $garment->quantity_in - $garment->quantity_out;

        return [
            $garment->id,
            $garment->pv,
            $garment->client->name ?? 'N/A',
            $garment->size,
            $garment->color,
            // --- MAPEO DE CANTIDADES ---
            $garment->quantity_in, // Cantidad que entró
            $garment->quantity_out, // Cantidad que salió (entregada)
            $quantityPending, // Cantidad restante
            // ---------------------------
            $garment->stitchingLine->name ?? 'N/A',
            $garment->motive->name ?? 'N/A',
            ucfirst($garment->audit_level),
            $garment->delivery_in_date ? $garment->delivery_in_date->format('Y-m-d H:i') : '',
            // Usamos el campo status de la DB, que ya se actualiza correctamente
            ucfirst($garment->status),
            $garment->registeredByUser->name ?? 'N/A',
            // Si está entregado, mostramos la fecha de entrega final, sino se deja vacío
            ($garment->status === 'entregado') ? $garment->delivery_out_date->format('Y-m-d H:i') : '',
            $garment->deliveredByUser->name ?? 'N/A',
            $garment->received_by ?? '', // Nombre de la persona que recibió (si aplica)
        ];
    }
}
