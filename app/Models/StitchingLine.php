<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StitchingLine extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name', 'is_external_service'];

    public function garments(): HasMany
    {
        return $this->hasMany(Garment::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Registra cualquier cambio en los campos definidos
            ->logOnlyDirty() // Solo registra los atributos que realmente cambiaron
            ->dontSubmitEmptyLogs(); // No guarda logs si no hubo cambios reales
    }
}
