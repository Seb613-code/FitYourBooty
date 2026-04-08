<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeanceSerie extends Model
{
    protected $table = 'seance_series';

    protected $fillable = [
        'seance_exercice_id',
        'numero',
        'effectuee',
        'reps',
        'poids',
    ];

    protected $casts = [
        'effectuee' => 'boolean',
    ];

    public function seanceExercice(): BelongsTo
    {
        return $this->belongsTo(SeanceExercice::class, 'seance_exercice_id');
    }
}
