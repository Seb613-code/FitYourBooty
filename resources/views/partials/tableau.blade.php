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
                    <tr data-editable-row>
                        <td data-order="{{ $donnee->date }}">{{ \Carbon\Carbon::parse($donnee->date)->format('d-m-Y') }}</td>
                        <td>
                            <span data-display>{{ $donnee->poids }}</span>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" step="0.1" name="poids" class="form-control form-control-sm d-none" value="{{ $donnee->poids }}" disabled data-edit-input>
                        </td>
                        <td>
                            <span data-display>{{ $donnee->pas }}</span>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" name="pas" min="0" class="form-control form-control-sm d-none" value="{{ $donnee->pas }}" disabled data-edit-input>
                        </td>
                        <td>
                            <span data-display>{{ $donnee->calories }}</span>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" name="calories" class="form-control form-control-sm d-none" value="{{ $donnee->calories }}" disabled data-edit-input>
                        </td>
                        <td>
                            <span data-display>{{ $donnee->proteines }}</span>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" step="0.1" name="proteines" class="form-control form-control-sm d-none" value="{{ $donnee->proteines }}" disabled data-edit-input>
                        </td>
                        <td>
                            <span data-display>{{ $donnee->lipides }}</span>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" step="0.1" name="lipides" class="form-control form-control-sm d-none" value="{{ $donnee->lipides }}" disabled data-edit-input>
                        </td>
                        <td>
                            <span data-display>{{ $donnee->glucides }}</span>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" step="0.1" name="glucides" class="form-control form-control-sm d-none" value="{{ $donnee->glucides }}" disabled data-edit-input>
                        </td>
                        <td>
                            <span data-display>{{ $donnee->depenses }}</span>
                            <input form="donnee-update-{{ $donnee->id }}" type="number" step="0.01" name="depenses" class="form-control form-control-sm d-none" value="{{ $donnee->depenses }}" disabled data-edit-input>
                        </td>
                        <td>
                            <span data-display>{{ $donnee->etiquettes }}</span>
                            <input form="donnee-update-{{ $donnee->id }}" type="text" name="etiquettes" class="form-control form-control-sm d-none" value="{{ $donnee->etiquettes }}" disabled data-edit-input>
                        </td>
                        <td>
                            <div class="d-flex gap-2 flex-wrap">
                                <form id="donnee-update-{{ $donnee->id }}" method="POST" action="{{ route('donnees.update', $donnee->id) }}">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ \Carbon\Carbon::parse($donnee->date)->format('Y-m-d') }}">
                                    <button class="btn btn-sm btn-outline-light" type="button" data-edit-button>Modifier</button>
                                    <button class="btn btn-sm btn-primary d-none" type="submit" data-save-button>Enregistrer</button>
                                    <button class="btn btn-sm btn-secondary d-none" type="button" data-cancel-button>Annuler</button>
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
