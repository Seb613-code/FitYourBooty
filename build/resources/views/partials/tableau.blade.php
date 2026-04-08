<div class="card shadow-sm mb-4">
    <div class="card-header bg-secondary text-white">Données enregistrées</div>
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-5">
                <label for="date-min">Date de début</label>
                <input type="date" id="date-min" class="form-control">
            </div>
            <div class="col-md-5">
                <label for="date-max">Date de fin</label>
                <input type="date" id="date-max" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-outline-secondary w-100" id="reset-filtres">Réinitialiser</button>
            </div>
        </div>
        <div style="max-height: 500px; overflow-y: auto;">
            <table class="table table-hover mb-0" id="donnees-table">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Poids (kg)</th>
                        <th>Calories</th>
                        <th>Protéines (g)</th>
                        <th>Lipides (g)</th>
                        <th>Glucides (g)</th>
                        <th>Dépenses (€)</th>
                        <th>Étiquettes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donnees as $donnee)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($donnee->date)->format('d-m-Y') }}</td>
                            <td>{{ $donnee->poids }}</td>
                            <td>{{ $donnee->calories }}</td>
                            <td>{{ $donnee->proteines }}</td>
                            <td>{{ $donnee->lipides }}</td>
                            <td>{{ $donnee->glucides }}</td>
                            <td>{{ $donnee->depenses }}</td>
                            <td>{{ $donnee->etiquettes }}</td>
                            <td>
                                <form method="POST" action="{{ route('donnees.destroy', $donnee->id) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
