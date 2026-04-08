<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donnee;
use Illuminate\Support\Facades\Auth;

class DonneeController extends Controller
{
    public function index()
    {
        $donnees = Donnee::where('user_id', Auth::id())
                         ->orderByDesc('date')
                         ->get();

        return view('dashboard', compact('donnees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'poids' => 'nullable|numeric',
            'calories' => 'nullable|numeric',
            'proteines' => 'nullable|numeric',
            'lipides' => 'nullable|numeric',
            'glucides' => 'nullable|numeric',
            'depenses' => 'nullable|numeric',
            'etiquettes' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();
        Donnee::create($data);

        return redirect()->route('dashboard');
    }

    public function destroy(Donnee $donnee)
    {
        if ($donnee->user_id === Auth::id()) {
            $donnee->delete();
        }

        return redirect()->route('dashboard');
    }

    public function importCsv(Request $request)
    {
        $userId = auth()->id();

        $handle = fopen($request->file('csv')->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');

        function toDecimal($value) {
            if ($value === '' || $value === null) {
                return null;
            }
            $value = preg_replace('/[\x{00A0}\x{202F}\s]+/u', '', $value);
            return str_replace(',', '.', $value);
        }

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $ligne = array_combine($header, $row);
            $date = \DateTime::createFromFormat('d-m-Y', $ligne['date']);

            Donnee::create([
                'user_id' => $userId,
                'date' => $date ? $date->format('Y-m-d') : null,
                'poids' => toDecimal($ligne['poids']),
                'calories' => toDecimal($ligne['calories']),
                'proteines' => toDecimal($ligne['proteines']),
                'lipides' => toDecimal($ligne['lipides']),
                'glucides' => toDecimal($ligne['glucides']),
                'depenses' => toDecimal($ligne['depenses']),
                'etiquettes' => $ligne['etiquettes'] ?: null,
            ]);
        }

        fclose($handle);

        return redirect()->back()->with('success', 'Fichier CSV importé avec succès !');
    }

    public function update(Request $request, Donnee $donnee)
    {
        if ($donnee->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'date' => 'required|date',
            'poids' => 'nullable|numeric',
            'calories' => 'nullable|numeric',
            'proteines' => 'nullable|numeric',
            'lipides' => 'nullable|numeric',
            'glucides' => 'nullable|numeric',
            'depenses' => 'nullable|numeric',
            'etiquettes' => 'nullable|string',
        ]);

        $donnee->update($data);

        return redirect()->route('dashboard')->with('success', 'Donnée mise à jour avec succès.');
    }
}
