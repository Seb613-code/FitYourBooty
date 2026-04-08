<?php

namespace App\Http\Controllers;

use App\Models\Exercice;
use App\Models\Seance;
use App\Models\SeanceCategorie;
use App\Models\SeanceType;
use Illuminate\Http\Request;

class ExerciceGestionController extends Controller
{
    public function index()
    {
        $categories = SeanceCategorie::query()
            ->with('types.exercices')
            ->orderBy('nom')
            ->get();

        return view('exercices', compact('categories'));
    }

    public function activite()
    {
        $categories = SeanceCategorie::query()
            ->with('types')
            ->orderBy('nom')
            ->get();

        $seances = Seance::query()
            ->with('type:id,nom,code,categorie_id', 'type.categorie:id,nom', 'exercices.series', 'exercices.exercice:id,nom')
            ->where('user_id', auth()->id())
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get()
            ->map(function ($seance) {
                $charge = 0;
                $categorieNom = $seance->type?->categorie?->nom ?? '';
                $isCardio = strtolower($categorieNom) === 'cardio';

                $seance->is_cardio = $isCardio;

                if (!$isCardio) {
                    foreach ($seance->exercices as $exercice) {
                        foreach ($exercice->series as $serie) {
                            if (!$serie->effectuee) {
                                continue;
                            }

                            $reps = $serie->reps ?? 0;
                            $poids = $serie->poids ?? 0;
                            $charge += $reps * $poids;
                        }
                    }
                }

                $seance->charge_deplacee = $charge;

                return $seance;
            });

        $seanceChartData = $seances->map(function ($seance) {
            return [
                'date' => $seance->date,
                'type' => $seance->type->nom ?? '',
                'type_code' => $seance->type->code ?? '',
                'categorie' => $seance->type?->categorie?->nom ?? '',
                'is_cardio' => $seance->is_cardio ?? false,
                'duration_minutes' => $seance->duration_minutes,
                'calories' => $seance->calories,
                'exercices' => $seance->exercices->map(function ($exercice) {
                    return [
                        'nom' => $exercice->exercice->nom ?? '',
                        'series' => $exercice->series->map(function ($serie) {
                            return [
                                'done' => $serie->effectuee,
                                'reps' => $serie->reps,
                                'poids' => $serie->poids,
                            ];
                        })->values()->all(),
                    ];
                })->values()->all(),
            ];
        })->values()->all();

        return view('activite', compact('categories', 'seances', 'seanceChartData'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        SeanceCategorie::create($data);

        return redirect()->route('exercices.gestion')->with('success', 'Catégorie ajoutée.');
    }

    public function storeType(Request $request)
    {
        $data = $request->validate([
            'categorie_id' => 'required|exists:seance_categories,id',
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:seance_types,code',
        ]);

        SeanceType::create($data);

        return redirect()->route('exercices.gestion')->with('success', 'Type ajouté.');
    }

    public function storeExercice(Request $request)
    {
        $data = $request->validate([
            'seance_type_id' => 'required|exists:seance_types,id',
            'nom' => 'required|string|max:255',
        ]);

        Exercice::create($data);

        return redirect()->route('exercices.gestion')->with('success', 'Exercice ajouté.');
    }

    public function destroyExercice(Exercice $exercice)
    {
        $exercice->delete();

        return redirect()->route('exercices.gestion')->with('success', 'Exercice supprimé.');
    }
}
