<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Garment extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'delivery_in_date' => 'datetime',
        'delivery_out_date' => 'datetime',
        'is_audit' => 'boolean',
        'quantity_in' => 'integer',
        'quantity_out' => 'integer',
        'defect_photo_path' => 'string',
        'sizes' => 'array', // CAMBIO CLAVE: Castear el nuevo campo 'sizes' a array/JSON
    ];

    // ... (Métodos de Accesor/Mutator) ...

    public function getCalculatedStatusAttribute()
    {
        if ($this->quantity_in == $this->quantity_out) {
            return 'entregado';
        }
        if ($this->quantity_in > $this->quantity_out) {
            return 'pendiente';
        }
        return 'cerrado';
    }

    public function getQuantityPendingAttribute(): int
    {
        return $this->quantity_in - $this->quantity_out;
    }

    // ... (Relaciones) ...

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

    public function registeredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }

    public function deliveredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by_user_id');
    }

    // 1. Definir las opciones de registro
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
