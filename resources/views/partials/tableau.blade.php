<div class="data-table">
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
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Poids (kg)</th>
                    <th>Pas</th>
                    <th>Calories</th>
                    <th>Protéines (g)</th>
                    <th>Lipides (g)</th>
                    <th>Glucides (g)</th>
                    <th>Dépenses (Cal)</th>
                    <th>Étiquettes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donnees as $donnee)
                    <tr>
                        <td data-order="{{ $donnee->date }}">
                            <input
                                form="donnee-update-{{ $donnee->id }}"
                                type="date"
                                name="date"
                                class="form-control form-control-sm"
                                value="{{ \Carbon\Carbon::parse($donnee->date)->format('Y-m-d') }}"
                                required
                            >
                        </td>
                        <td>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" step="0.1" name="poids" class="form-control form-control-sm" value="{{ $donnee->poids }}">
                        </td>
                        <td>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" name="pas" min="0" class="form-control form-control-sm" value="{{ $donnee->pas }}">
                        </td>
                        <td>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" name="calories" class="form-control form-control-sm" value="{{ $donnee->calories }}">
                        </td>
                        <td>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" step="0.1" name="proteines" class="form-control form-control-sm" value="{{ $donnee->proteines }}">
                        </td>
                        <td>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" step="0.1" name="lipides" class="form-control form-control-sm" value="{{ $donnee->lipides }}">
                        </td>
                        <td>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" step="0.1" name="glucides" class="form-control form-control-sm" value="{{ $donnee->glucides }}">
                        </td>
                        <td>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" step="0.01" name="depenses" class="form-control form-control-sm" value="{{ $donnee->depenses }}">
                        </td>
                        <td>
                            <input form="donnee-update-{{ $donnee->id }}" type="text" name="etiquettes" class="form-control form-control-sm" value="{{ $donnee->etiquettes }}">
                        </td>
                        <td>
                            <div class="d-flex gap-2 flex-wrap">
                                <form id="donnee-update-{{ $donnee->id }}" method="POST" action="{{ route('donnees.update', $donnee->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-primary" type="submit">Enregistrer</button>
                                </form>
                                <form method="POST" action="{{ route('donnees.destroy', $donnee->id) }}" onsubmit="return confirm('Supprimer cette entrée ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
