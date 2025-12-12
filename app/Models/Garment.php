<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Garment extends Model
{
    protected $guarded = []; // Puedes usar $fillable o $guarded, por ahora $guarded es más simple

    protected $casts = [
        'delivery_in_date' => 'datetime',
        'delivery_out_date' => 'datetime',
        'is_audit' => 'boolean',
        'quantity_in' => 'integer', // Cambiado de 'quantity' a 'quantity_in'
        'quantity_out' => 'integer', // NUEVO: Cantidad entregada
        'defect_photo_path' => 'string',
    ];

    public function getCalculatedStatusAttribute()
    {
        // Si la cantidad de entrada es igual a la de salida, está entregado.
        if ($this->quantity_in == $this->quantity_out) {
            return 'entregado';
        }

        // Si la cantidad de entrada es mayor que la de salida, está pendiente.
        if ($this->quantity_in > $this->quantity_out) {
            return 'pendiente';
        }

        // En cualquier otro caso (aunque no debería pasar, como quantity_out > quantity_in)
        return 'cerrado';
    }

    // Helper para obtener la cantidad pendiente
    public function getQuantityPendingAttribute(): int
    {
        return $this->quantity_in - $this->quantity_out;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function stitchingLine(): BelongsTo
    {
        return $this->belongsTo(StitchingLine::class);
    }

    public function motive(): BelongsTo
    {
        return $this->belongsTo(Motive::class);
    }

    // Usuarios de registro/entrega (usando alias)
    public function registeredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }

    public function deliveredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by_user_id');
    }
}
