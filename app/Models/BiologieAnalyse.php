<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiologieAnalyse extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'remarques',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function mesures()
    {
        return $this->hasMany(BiologieMesure::class, 'analyse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
