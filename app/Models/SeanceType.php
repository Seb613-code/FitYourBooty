<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeanceType extends Model
{
    protected $table = 'seance_types';

    protected $fillable = [
        'categorie_id',
        'nom',
        'code',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(SeanceCategorie::class, 'categorie_id');
    }

    public function exercices(): HasMany
    {
        return $this->hasMany(Exercice::class, 'seance_type_id');
    }
}
