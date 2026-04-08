<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exercice extends Model
{
    protected $fillable = [
        'nom',
        'seance_type_id',
    ];

    public function seanceType(): BelongsTo
    {
        return $this->belongsTo(SeanceType::class, 'seance_type_id');
    }
}
