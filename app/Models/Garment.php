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
    ];

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
