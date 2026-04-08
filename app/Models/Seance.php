<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seance extends Model
{
    protected $fillable = [
        'user_id',
        'seance_type_id',
        'date',
        'duration_minutes',
        'calories',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(SeanceType::class, 'seance_type_id');
    }

    public function exercices(): HasMany
    {
        return $this->hasMany(SeanceExercice::class, 'seance_id');
    }
}
