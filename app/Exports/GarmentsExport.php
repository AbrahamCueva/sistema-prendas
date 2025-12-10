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
            'Cantidad',
            'Línea de Costura',
            'Motivo de Arreglo',
            'Nivel Auditoría',
            'Fecha Entrada',
            'Estado',
            'Usuario Registro',
            'Fecha Entrega',
            'Usuario Entrega',
        ];
    }

    /**
     * Mapea cada objeto Garment a una fila de la hoja de cálculo.
     */
    public function map($garment): array
    {
        return [
            $garment->id,
            $garment->pv,
            $garment->client->name ?? 'N/A',
            $garment->size,
            $garment->color,
            $garment->quantity,
            $garment->stitchingLine->name ?? 'N/A',
            $garment->motive->name ?? 'N/A',
            ucfirst($garment->audit_level),
            $garment->delivery_in_date ? $garment->delivery_in_date->format('Y-m-d H:i') : '',
            ucfirst($garment->status),
            $garment->registeredByUser->name ?? 'N/A',
            $garment->delivery_out_date ? $garment->delivery_out_date->format('Y-m-d H:i') : 'PENDIENTE',
            $garment->deliveredByUser->name ?? 'N/A',
        ];
    }
}
