<?php

namespace App\Http\Controllers;

use App\Models\BiologieAnalyse;
use App\Models\BiologieMesure;
use App\Models\BiologieParametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BiologieController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $parametres = BiologieParametre::orderByRaw('COALESCE(sort_order, 9999)')->orderBy('label')->get();
        $analyses = BiologieAnalyse::where('user_id', $userId)
            ->orderBy('date')
            ->with(['mesures.parametre' => function ($query) {
                $query->select('id', 'key');
            }])
            ->get();

        $parametresPayload = $parametres->map(function ($parametre) {
            return [
                'key' => $parametre->key,
                'label' => $parametre->label,
                'refMin' => $parametre->ref_min,
                'refMax' => $parametre->ref_max,
                'sortOrder' => $parametre->sort_order,
            ];
        })->values();

        $analysesPayload = $analyses->map(function ($analyse) {
            return [
                'id' => $analyse->id,
                'date' => $analyse->date->format('Y-m-d'),
                'displayDate' => $analyse->date->format('d/m/Y'),
                'remarks' => $analyse->remarques,
                'values' => $analyse->mesures->mapWithKeys(function ($mesure) {
                    return [$mesure->parametre?->key => $mesure->valeur];
                })->filter()->all(),
            ];
        })->values();

        return view('biologie', compact('parametres', 'analyses', 'parametresPayload', 'analysesPayload'));
    }

    public function store(Request $request)
    {
        $mesuresInput = collect($request->input('mesures', []))->map(function ($value) {
            if ($value === null || $value === '') {
                return null;
            }
            $cleaned = preg_replace('/[\x{00A0}\x{202F}\s]+/u', '', (string) $value);
            $cleaned = str_replace(',', '.', $cleaned);
            return $cleaned;
        })->all();

        $request->merge(['mesures' => $mesuresInput]);

        $data = $request->validate([
            'date' => 'required|date',
            'remarques' => 'nullable|string',
            'mesures' => 'required|array',
            'mesures.*' => 'nullable|numeric',
        ]);

        $userId = Auth::id();
        $parametres = BiologieParametre::all(['id', 'key'])->keyBy('key');

        $analyse = BiologieAnalyse::create([
            'user_id' => $userId,
            'date' => $data['date'],
            'remarques' => $data['remarques'] ?? null,
        ]);

        $mesures = [];
        foreach ($data['mesures'] as $key => $valeur) {
            if (!$parametres->has($key)) {
                continue;
            }
            if ($valeur === null || $valeur === '') {
                continue;
            }
            if (!is_numeric($valeur)) {
                continue;
            }
            $mesures[] = [
                'analyse_id' => $analyse->id,
                'parametre_id' => $parametres[$key]->id,
                'valeur' => $valeur,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($mesures) {
            BiologieMesure::insert($mesures);
        }

        return redirect()->route('biologie')->with('success', "Analyse enregistree.");
    }

    public function update(Request $request, BiologieAnalyse $analyse)
    {
        if ($analyse->user_id !== Auth::id()) {
            abort(403);
        }

        $mesuresInput = collect($request->input('mesures', []))->map(function ($value) {
            if ($value === null || $value === '') {
                return null;
            }
            $cleaned = preg_replace('/[\x{00A0}\x{202F}\s]+/u', '', (string) $value);
            $cleaned = str_replace(',', '.', $cleaned);
            return $cleaned;
        })->all();

        $request->merge(['mesures' => $mesuresInput]);

        $data = $request->validate([
            'date' => 'required|date',
            'remarques' => 'nullable|string',
            'mesures' => 'required|array',
            'mesures.*' => 'nullable|numeric',
        ]);

        $parametres = BiologieParametre::all(['id', 'key'])->keyBy('key');

        $mesures = [];
        foreach ($data['mesures'] as $key => $valeur) {
            if (!$parametres->has($key)) {
                continue;
            }
            if ($valeur === null || $valeur === '') {
                continue;
            }
            if (!is_numeric($valeur)) {
                continue;
            }
            $mesures[] = [
                'analyse_id' => $analyse->id,
                'parametre_id' => $parametres[$key]->id,
                'valeur' => $valeur,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($analyse, $data, $mesures) {
            $analyse->update([
                'date' => $data['date'],
                'remarques' => $data['remarques'] ?? null,
            ]);
            $analyse->mesures()->delete();
            if ($mesures) {
                BiologieMesure::insert($mesures);
            }
        });

        return redirect()->route('biologie')->with('success', "Analyse mise a jour.");
    }

    public function destroy(BiologieAnalyse $analyse)
    {
        if ($analyse->user_id !== Auth::id()) {
            abort(403);
        }

        $analyse->delete();

        return redirect()->route('biologie')->with('success', "Analyse supprimee.");
    }
}
