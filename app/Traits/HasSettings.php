<?php

namespace App\Traits;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache; // Usaremos la caché para velocidad

trait HasSettings
{
    // NO necesitamos definir la constante aquí ya

    /**
     * Obtiene una configuración por clave, usando caché.
     */
    public static function getSetting(string $key, $default = null)
    {
        // Acceder a la constante a través del modelo Setting::
        $settings = Cache::rememberForever(Setting::SETTINGS_CACHE_KEY, function () {
            return Setting::pluck('value', 'key')->all();
        });

        return Arr::get($settings, $key, $default);
    }

    /**
     * Guarda o actualiza una configuración y limpia la caché.
     */
    public static function setSetting(string $key, $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        // Limpiar caché después de guardar
        Cache::forget(Setting::SETTINGS_CACHE_KEY); // Acceder a la constante a través del modelo Setting::
    }
}
