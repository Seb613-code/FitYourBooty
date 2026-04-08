@extends('layouts.app')

@section('body-class', 'theme-activity')

@section('content')
<div class="activity-page">
    <div class="container py-4">
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">Données</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('activite') }}">Activité</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('biologie') }}">Biologie</a>
        </li>
    </ul>

    <div class="card shadow-sm">
        <form method="POST" action="{{ route('activite.update', $seance) }}" id="activite-edit-form">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="date" class="form-label">Date</label>
                        <input
                            type="date"
                            id="date"
                            name="date"
                            class="form-control"
                            value="{{ $seance->date }}"
                            required
                        >
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="form-label">Type de séance</label>
                        <input type="text" class="form-control" value="{{ $seance->type->nom ?? '—' }}" disabled>
                    </div>
                </div>

                <div class="mt-4 {{ ($isCardio ?? false) ? 'd-none' : '' }}" id="musculation-section">
                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
                        <h5 class="mb-0">Exercices</h5>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="add-exercice">Ajouter un exercice</button>
                    </div>

                    <div id="exercices-container" class="d-grid gap-3"></div>
                </div>

                <div class="mt-4 {{ ($isCardio ?? false) ? '' : 'd-none' }}" id="cardio-section">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="duration_minutes" class="form-label">Durée (min)</label>
                            <input
                                type="number"
                                min="0"
                                step="1"
                                id="duration_minutes"
                                name="duration_minutes"
                                class="form-control"
                                value="{{ $seance->duration_minutes ?? '' }}"
                                {{ ($isCardio ?? false) ? 'required' : '' }}
                            >
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="calories" class="form-label">Calories</label>
                            <input
                                type="number"
                                min="0"
                                step="1"
                                id="calories"
                                name="calories"
                                class="form-control"
                                value="{{ $seance->calories ?? '' }}"
                                {{ ($isCardio ?? false) ? 'required' : '' }}
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('activite') }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>

    <template id="exercice-template">
        <div class="card border exercise-card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
                    <div class="flex-grow-1">
                        <label class="form-label">Exercice</label>
                        <select
                            name="exercices[__INDEX__][exercice_id]"
                            class="form-select"
                            required
                        >
                            <option value="" disabled selected>Choisir un exercice</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm mt-3 mt-md-0 remove-exercice">Retirer</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Série</th>
                                <th scope="col">Effectuée</th>
                                <th scope="col">Reps</th>
                                <th scope="col">Poids (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 6; $i++)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="exercices[__INDEX__][series][{{ $i }}][done]"
                                                value="1"
                                            >
                                        </div>
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            min="0"
                                            step="1"
                                            class="form-control form-control-sm"
                                            name="exercices[__INDEX__][series][{{ $i }}][reps]"
                                        >
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            min="0"
                                            step="0.5"
                                            class="form-control form-control-sm"
                                            name="exercices[__INDEX__][series][{{ $i }}][weight]"
                                        >
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </template>
    </div>
</div>

<script type="application/json" id="activite-edit-data">@json(['isCardio' => $isCardio ?? false, 'exercicesOptions' => $exerciceOptions, 'existing' => $existingExercices])</script>
<script>
    (function () {
        const exercicesContainer = document.getElementById('exercices-container');
        const addExerciceButton = document.getElementById('add-exercice');
        const template = document.getElementById('exercice-template');
        const seedData = JSON.parse(document.getElementById('activite-edit-data').textContent || '{}');
        const isCardio = seedData.isCardio || false;
        const exercicesOptions = seedData.exercicesOptions || [];
        const existing = seedData.existing || [];
        let exerciceIndex = 0;

        function buildOptions(options) {
            return options
                .map(option => `<option value="${option.id}">${option.nom}</option>`)
                .join('');
        }

        function addExerciceBlock(values) {
            const html = template.innerHTML.replaceAll('__INDEX__', String(exerciceIndex));
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            const block = wrapper.firstElementChild;
            const select = block.querySelector('select');

            select.innerHTML = '<option value="" disabled selected>Choisir un exercice</option>' + buildOptions(exercicesOptions);
            if (values?.exercice_id) {
                select.value = String(values.exercice_id);
            }

            block.querySelector('.remove-exercice').addEventListener('click', () => {
                block.remove();
            });

            if (values?.series) {
                Object.entries(values.series).forEach(([numero, serie]) => {
                    const base = `exercices[${exerciceIndex}][series][${numero}]`;
                    const done = block.querySelector(`input[name='${base}[done]']`);
                    const reps = block.querySelector(`input[name='${base}[reps]']`);
                    const weight = block.querySelector(`input[name='${base}[weight]']`);

                    if (done && serie.done) {
                        done.checked = true;
                    }
                    if (reps && serie.reps !== null) {
                        reps.value = serie.reps;
                    }
                    if (weight && serie.weight !== null) {
                        weight.value = serie.weight;
                    }
                });
            }

            exercicesContainer.appendChild(block);
            exerciceIndex += 1;
        }

        if (!isCardio) {
            addExerciceButton.addEventListener('click', () => addExerciceBlock());

            if (existing.length) {
                existing.forEach(item => addExerciceBlock(item));
            } else {
                addExerciceBlock();
            }
        }
    })();
</script>
@endsection
