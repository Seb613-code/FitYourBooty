<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiologieParametre extends Model
{
    protected $fillable = [
        'key',
        'label',
        'sort_order',
        'ref_min',
        'ref_max',
    ];

    public function mesures()
    {
        return $this->hasMany(BiologieMesure::class, 'parametre_id');
    }
}
