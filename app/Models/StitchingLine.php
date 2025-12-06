<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StitchingLine extends Model
{
    protected $fillable = ['name', 'is_external_service'];

    public function garments(): HasMany
    {
        return $this->hasMany(Garment::class);
    }
}
