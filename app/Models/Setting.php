<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Setting extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $guarded = [];

    // Mover la constante aquí
    const SETTINGS_CACHE_KEY = 'global_app_settings';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Registra cualquier cambio en los campos definidos
            ->logOnlyDirty() // Solo registra los atributos que realmente cambiaron
            ->dontSubmitEmptyLogs(); // No guarda logs si no hubo cambios reales
    }
}
