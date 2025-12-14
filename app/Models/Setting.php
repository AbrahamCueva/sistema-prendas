<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    // Mover la constante aquí
    const SETTINGS_CACHE_KEY = 'global_app_settings';
}
