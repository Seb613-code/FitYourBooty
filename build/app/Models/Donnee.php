<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donnee extends Model
{
protected $fillable = [
    'user_id',
    'date',
    'poids',
    'calories',
    'proteines',
    'lipides',
    'glucides',
    'depenses',
    'etiquettes',
];
}
