<?php

namespace App\Http\Controllers;

use App\Models\Seance;
use App\Models\SeanceExercice;
use App\Models\SeanceSerie;
use App\Models\SeanceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeanceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'type' => 'required|string|exists:seance_types,code',
            'duration_minutes' => 'nullable|integer|min:0',
            'calories' => 'nullable|integer|min:0',
            'exercices' => 'nullable|array|min:1',
            'exercices.*.exercice_id' => 'required_with:exercices|exists:exercices,id',
            'exercices.*.series' => 'required_with:exercices|array',
            'exercices.*.series.*.done' => 'nullable|boolean',
            'exercices.*.series.*.reps' => 'nullable|integer|min:0',
            'exercices.*.series.*.weight' => 'nullable|numeric|min:0',
        ]);

        $seanceType = SeanceType::query()
            ->with('categorie:id,nom')
            ->where('code', $data['type'])
            ->first();

        $seanceTypeId = $seanceType?->id;
        if (!$seanceTypeId) {
            return redirect()->route('activite')->withErrors(['type' => 'Type de séance invalide.']);
        }

        $categorieNom = $seanceType?->categorie?->nom ?? '';
        $isCardio = strtolower($categorieNom) === 'cardio';

        if ($isCardio) {
            if ($data['duration_minutes'] === null || $data['calories'] === null) {
                return redirect()->route('activite')->withErrors([
                    'duration_minutes' => 'Renseigne la durée pour une séance cardio.',
                    'calories' => 'Renseigne les calories pour une séance cardio.',
                ]);
            }
        } else {
            if (empty($data['exercices'])) {
                return redirect()->route('activite')->withErrors([
                    'exercices' => 'Ajoute au moins un exercice.',
                ]);
            }
        }

        DB::transaction(function () use ($data, $seanceTypeId, $isCardio) {
            $seance = Seance::create([
                'user_id' => Auth::id(),
                'seance_type_id' => $seanceTypeId,
                'date' => $data['date'],
                'duration_minutes' => $isCardio ? $data['duration_minutes'] : null,
                'calories' => $isCardio ? $data['calories'] : null,
            ]);

            if ($isCardio) {
                return;
            }

            foreach ($data['exercices'] as $index => $exerciceData) {
                $seanceExercice = SeanceExercice::create([
                    'seance_id' => $seance->id,
                    'exercice_id' => $exerciceData['exercice_id'],
                    'ordre' => $index + 1,
                ]);

                foreach ($exerciceData['series'] as $numero => $serieData) {
                    $effectuee = !empty($serieData['done']);
                    $reps = $serieData['reps'] ?? null;
                    $poids = $serieData['weight'] ?? null;

                    SeanceSerie::create([
                        'seance_exercice_id' => $seanceExercice->id,
                        'numero' => (int) $numero,
                        'effectuee' => $effectuee,
                        'reps' => $reps === '' ? null : $reps,
                        'poids' => $poids === '' ? null : $poids,
                    ]);
                }
            }
        });

        return redirect()->route('activite')->with('success', 'Séance enregistrée.');
    }

    public function edit(Seance $seance)
    {
        if ($seance->user_id !== Auth::id()) {
            abort(403);
        }

        $seance->load(['type:id,nom,code,categorie_id', 'type.categorie:id,nom', 'type.exercices:id,nom,seance_type_id', 'exercices.exercice', 'exercices.series']);

        $exerciceOptions = $seance->type?->exercices
            ? $seance->type->exercices->map(fn ($ex) => ['id' => $ex->id, 'nom' => $ex->nom])->values()->all()
            : [];

        $existingExercices = $seance->exercices->map(function ($item) {
            return [
                'exercice_id' => $item->exercice_id,
                'series' => $item->series->keyBy('numero')->map(function ($serie) {
                    return [
                        'done' => $serie->effectuee,
                        'reps' => $serie->reps,
                        'weight' => $serie->poids,
                    ];
                })->toArray(),
            ];
        })->values()->all();

        $categorieNom = $seance->type?->categorie?->nom ?? '';
        $isCardio = strtolower($categorieNom) === 'cardio';

        return view('activite-edit', compact('seance', 'exerciceOptions', 'existingExercices', 'isCardio'));
    }

    public function update(Request $request, Seance $seance)
    {
        if ($seance->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'date' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:0',
            'calories' => 'nullable|integer|min:0',
            'exercices' => 'nullable|array|min:1',
            'exercices.*.exercice_id' => 'required_with:exercices|exists:exercices,id',
            'exercices.*.series' => 'required_with:exercices|array',
            'exercices.*.series.*.done' => 'nullable|boolean',
            'exercices.*.series.*.reps' => 'nullable|integer|min:0',
            'exercices.*.series.*.weight' => 'nullable|numeric|min:0',
        ]);

        $seance->loadMissing('type.categorie');
        $categorieNom = $seance->type?->categorie?->nom ?? '';
        $isCardio = strtolower($categorieNom) === 'cardio';

        if ($isCardio) {
            if ($data['duration_minutes'] === null || $data['calories'] === null) {
                return redirect()->route('activite.edit', $seance)->withErrors([
                    'duration_minutes' => 'Renseigne la durée pour une séance cardio.',
                    'calories' => 'Renseigne les calories pour une séance cardio.',
                ]);
            }
        } else {
            if (empty($data['exercices'])) {
                return redirect()->route('activite.edit', $seance)->withErrors([
                    'exercices' => 'Ajoute au moins un exercice.',
                ]);
            }
        }

        DB::transaction(function () use ($data, $seance, $isCardio) {
            $seance->update([
                'date' => $data['date'],
                'duration_minutes' => $isCardio ? $data['duration_minutes'] : null,
                'calories' => $isCardio ? $data['calories'] : null,
            ]);

            $seance->exercices()->delete();

            if ($isCardio) {
                return;
            }

            foreach ($data['exercices'] as $index => $exerciceData) {
                $seanceExercice = SeanceExercice::create([
                    'seance_id' => $seance->id,
                    'exercice_id' => $exerciceData['exercice_id'],
                    'ordre' => $index + 1,
                ]);

                foreach ($exerciceData['series'] as $numero => $serieData) {
                    $effectuee = !empty($serieData['done']);
                    $reps = $serieData['reps'] ?? null;
                    $poids = $serieData['weight'] ?? null;

                    SeanceSerie::create([
                        'seance_exercice_id' => $seanceExercice->id,
                        'numero' => (int) $numero,
                        'effectuee' => $effectuee,
                        'reps' => $reps === '' ? null : $reps,
                        'poids' => $poids === '' ? null : $poids,
                    ]);
                }
            }
        });

        return redirect()->route('activite')->with('success', 'Séance mise à jour.');
    }

    public function destroy(Seance $seance)
    {
        if ($seance->user_id !== Auth::id()) {
            abort(403);
        }

        $seance->delete();

        return redirect()->route('activite')->with('success', 'Séance supprimée.');
    }
}
