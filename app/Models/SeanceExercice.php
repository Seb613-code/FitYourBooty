<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeanceExercice extends Model
{
    protected $table = 'seance_exercices';

    protected $fillable = [
        'seance_id',
        'exercice_id',
        'ordre',
    ];

    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class, 'seance_id');
    }

    public function exercice(): BelongsTo
    {
        return $this->belongsTo(Exercice::class, 'exercice_id');
    }

    public function series(): HasMany
    {
        return $this->hasMany(SeanceSerie::class, 'seance_exercice_id');
    }
}
