<?php

namespace App\Http\Controllers;

use App\Models\Donnee;
use Illuminate\Http\Request;
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
            'pas' => 'nullable|integer|min:0',
            'calories' => 'nullable|numeric',
            'proteines' => 'nullable|numeric',
            'lipides' => 'nullable|numeric',
            'glucides' => 'nullable|numeric',
            'depenses' => 'nullable|numeric',
            'etiquettes' => 'nullable|string',
        ]);

        $date = $data['date'];
        unset($data['date']);

        $this->mergeDonnee(Auth::id(), $date, $data);

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

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $ligne = array_combine($header, $row);
            $date = \DateTime::createFromFormat('d-m-Y', $ligne['date']);

            $this->mergeDonnee($userId, $date ? $date->format('Y-m-d') : null, [
                'poids' => $this->toDecimal($ligne['poids']),
                'pas' => isset($ligne['pas']) && $ligne['pas'] !== '' ? (int) $this->toDecimal($ligne['pas']) : null,
                'calories' => $this->toDecimal($ligne['calories']),
                'proteines' => $this->toDecimal($ligne['proteines']),
                'lipides' => $this->toDecimal($ligne['lipides']),
                'glucides' => $this->toDecimal($ligne['glucides']),
                'depenses' => $this->toDecimal($ligne['depenses']),
                'etiquettes' => $ligne['etiquettes'] ?: null,
            ]);
        }

        fclose($handle);

        return redirect()->back()->with('success', 'Fichier CSV importé avec succès !');
    }

    private function mergeDonnee(int $userId, ?string $date, array $values): void
    {
        $donnee = Donnee::firstOrNew([
            'user_id' => $userId,
            'date' => $date,
        ]);

        foreach ($values as $field => $value) {
            if ($value !== null && $value !== '') {
                $donnee->{$field} = $value;
            }
        }

        $donnee->save();
    }

    private function toDecimal(?string $value): ?string
    {
        if ($value === '' || $value === null) {
            return null;
        }

        $value = preg_replace('/[\x{00A0}\x{202F}\s]+/u', '', $value);

        return str_replace(',', '.', $value);
    }

    public function update(Request $request, Donnee $donnee)
    {
        if ($donnee->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'date' => 'required|date',
            'poids' => 'nullable|numeric',
            'pas' => 'nullable|integer|min:0',
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
