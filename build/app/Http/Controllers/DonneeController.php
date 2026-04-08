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
        $request->validate(['csv' => 'required|file|mimes:csv,txt']);
        $user = Auth::user();

        $handle = fopen($request->file('csv')->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            Donnee::create([
                'user_id' => $user->id,
                'date' => \Carbon\Carbon::createFromFormat('d-m-Y', $row[0])->format('Y-m-d'),
                'poids' => str_replace(',', '.', $row[1]),
                'calories' => str_replace(',', '.', $row[2]),
                'proteines' => str_replace(',', '.', $row[3]),
                'lipides' => str_replace(',', '.', $row[4]),
                'glucides' => str_replace(',', '.', $row[5]),
                'depenses' => str_replace(',', '.', $row[6]),
                'etiquettes' => $row[7] ?? '',
            ]);
        }

        fclose($handle);
        return redirect()->route('dashboard')->with('success', 'Importation réussie');
    }
}
