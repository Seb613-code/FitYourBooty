<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiologieMesure extends Model
{
    protected $fillable = [
        'analyse_id',
        'parametre_id',
        'valeur',
    ];

    public function analyse()
    {
        return $this->belongsTo(BiologieAnalyse::class, 'analyse_id');
    }

    public function parametre()
    {
        return $this->belongsTo(BiologieParametre::class, 'parametre_id');
    }
}
