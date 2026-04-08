<?php

namespace App\Http\Controllers;

use App\Models\Exercice;
use App\Models\SeanceType;
use Illuminate\Http\Request;

class ExerciceController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');

        $query = Exercice::query()
            ->select('exercices.id', 'exercices.nom', 'exercices.seance_type_id')
            ->with('seanceType:id,code');

        if ($type) {
            $seanceTypeId = SeanceType::where('code', $type)->value('id');
            $query->where('seance_type_id', $seanceTypeId ?: 0);
        }

        return response()->json($query->orderBy('nom')->get());
    }
}
