<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeanceCategorie extends Model
{
    protected $table = 'seance_categories';

    protected $fillable = [
        'nom',
    ];

    public function types(): HasMany
    {
        return $this->hasMany(SeanceType::class, 'categorie_id');
    }
}
