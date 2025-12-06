<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motive extends Model
{
    protected $fillable = ['name', 'type'];

    public function garments(): HasMany
    {
        return $this->hasMany(Garment::class);
    }
}
