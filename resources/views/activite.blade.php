@extends('layouts.app')

@section('body-class', 'theme-activity')

@section('content')
<div class="activity-page">
    <div class="page-hero mb-4">
        <div class="page-hero__content">
            <div class="eyebrow">Activité</div>
            <h1 class="page-title">Séances et progression</h1>
            <p class="page-subtitle">Analyse tes charges, tes séries et ton cardio en un tableau clair.</p>
        </div>
        <div class="page-hero__glow" aria-hidden="true"></div>
    </div>
    <ul class="nav nav-tabs app-tabs mb-4">
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

    <form method="POST" action="{{ route('activite.store') }}" class="section-card" id="activite-form">
        @csrf
        <div class="section-card__body">
            <div class="section-card__header mb-3">
                <div>
                    <h2 class="section-title">Nouvelle séance</h2>
                    <p class="section-subtitle">Saisis les exercices ou le cardio de la journée.</p>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label for="date" class="form-label">Date</label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        class="form-control"
                        value="{{ now()->toDateString() }}"
                        required
                    >
                </div>

                <div class="col-12 col-md-8">
                    <label for="type" class="form-label">Type de séance</label>
                    <select id="type" name="type" class="form-select" required>
                        <option value="" disabled selected>Choisir un type</option>
                        @php
                            $seenTypeCodes = [];
                        @endphp
                        @foreach ($categories as $categorie)
                            @php
                                $categoryTypes = $categorie->types->filter(function ($type) use (&$seenTypeCodes) {
                                    $typeCode = (string) $type->code;
                                    if (isset($seenTypeCodes[$typeCode])) {
                                        return false;
                                    }
                                    $seenTypeCodes[$typeCode] = true;
                                    return true;
                                });
                            @endphp
                            @if ($categoryTypes->isNotEmpty())
                                <optgroup label="{{ $categorie->nom }}">
                                    @foreach ($categoryTypes as $type)
                                        <option value="{{ $type->code }}">{{ $type->nom }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

                <div class="mt-4 d-none" id="musculation-section">
                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
                        <h5 class="mb-0">Exercices</h5>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="add-exercice">Ajouter un exercice</button>
                    </div>

                    <div id="exercices-container" class="d-grid gap-3"></div>
                </div>

                <div class="mt-4 d-none" id="cardio-section">
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
                            >
                        </div>
                    </div>
                </div>

        </div>

        <div class="section-card__footer text-end">
            <button type="submit" class="btn btn-primary btn-glow">Enregistrer la séance</button>
        </div>
    </form>

    <div class="section-card mt-4">
        <div class="section-card__body">
            <div class="section-card__header mb-3">
                <div>
                    <h2 class="section-title">Séances enregistrées</h2>
                    <p class="section-subtitle">Historique récent avec accès rapide aux actions.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Type</th>
                            <th scope="col">Charge / cardio</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($seances as $seance)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($seance->date)->format('d/m/Y') }}</td>
                                <td>{{ $seance->type->nom ?? '—' }}</td>
                                <td>
                                    @if ($seance->is_cardio)
                                        {{ $seance->duration_minutes ?? '—' }} min / {{ $seance->calories ?? '—' }} kcal
                                    @else
                                        {{ number_format($seance->charge_deplacee ?? 0, 0, ',', ' ') }} kg
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('activite.edit', $seance) }}" class="btn btn-outline-secondary btn-sm">Modifier</a>
                                        <form method="POST" action="{{ route('activite.destroy', $seance) }}" onsubmit="return confirm('Supprimer cette séance ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">Aucune séance enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="section-card mt-4">
        <div class="section-card__body">
            <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-3">
                <div>
                    <h2 class="section-title">Graphique activité</h2>
                    <p class="section-subtitle">Filtre par période et analyse tes tendances.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <label for="activity-date-start" class="form-label mb-0">Du</label>
                        <input type="date" id="activity-date-start" class="form-control form-control-sm">
                        <label for="activity-date-end" class="form-label mb-0">Au</label>
                        <input type="date" id="activity-date-end" class="form-control form-control-sm">
                    </div>
                    <select id="activity-chart-mode" class="form-select form-select-sm" style="min-width: 220px;">
                        <option value="seance_type">Charge par type de séance</option>
                        <option value="exercice">Charge par exercice</option>
                        <option value="cardio_calories">Cardio - calories</option>
                        <option value="cardio_duration">Cardio - durée</option>
                    </select>
                    <select id="activity-chart-series" class="form-select form-select-sm d-none" style="min-width: 220px;"></select>
                </div>
            </div>
            <div id="activity-chart" style="height: 420px;"></div>
        </div>
    </div>

    <div class="section-card mt-4">
        <div class="section-card__body">
            <div class="section-card__header mb-3">
                <div>
                    <h2 class="section-title">Gestion des exercices</h2>
                    <p class="section-subtitle">Accès direct aux catégories et types.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Lien</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gerer categories, types et exercices</td>
                            <td>
                                <a href="{{ route('exercices.gestion') }}" class="btn btn-outline-primary btn-sm">
                                    Ouvrir la gestion
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <template id="exercice-template">
        <div class="card border exercise-card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
                    <div class="flex-grow-1">
                        <label for="exercice___INDEX__" class="form-label">Exercice</label>
                        <select
                            id="exercice___INDEX__"
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
                                                id="exercice___INDEX___series_{{ $i }}_done"
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
                                            aria-label="Reps série {{ $i }}"
                                        >
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            min="0"
                                            step="0.5"
                                            class="form-control form-control-sm"
                                            name="exercices[__INDEX__][series][{{ $i }}][weight]"
                                            aria-label="Poids série {{ $i }}"
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
@php
    $typeCategoryMap = $categories
        ->flatMap(fn ($categorie) => $categorie->types->mapWithKeys(fn ($type) => [$type->code => $categorie->nom]))
        ->all();

    $previousSeances = $seances->map(function ($seance) {
        return [
            'id' => $seance->id,
            'type_code' => $seance->type->code ?? '',
            'date' => $seance->date,
            'is_cardio' => $seance->is_cardio ?? false,
            'duration_minutes' => $seance->duration_minutes,
            'calories' => $seance->calories,
            'exercices' => $seance->exercices->map(function ($exercice) {
                return [
                    'exercice_id' => $exercice->exercice_id,
                    'series' => $exercice->series
                        ->keyBy('numero')
                        ->map(function ($serie) {
                            return [
                                'done' => false,
                                'reps' => $serie->reps,
                                'weight' => $serie->poids,
                            ];
                        })
                        ->toArray(),
                ];
            })->values()->all(),
        ];
    })->values()->all();
@endphp
</div>

<script type="application/json" id="previous-seances-data">@json($previousSeances)</script>
<script type="application/json" id="type-category-data">@json($typeCategoryMap)</script>
<script type="application/json" id="activity-chart-data">@json($seanceChartData ?? [])</script>
<script>
    (function () {
        const typeSelect = document.getElementById('type');
        const exercicesContainer = document.getElementById('exercices-container');
        const addExerciceButton = document.getElementById('add-exercice');
        const template = document.getElementById('exercice-template');
        const musculationSection = document.getElementById('musculation-section');
        const cardioSection = document.getElementById('cardio-section');
        const durationInput = document.getElementById('duration_minutes');
        const caloriesInput = document.getElementById('calories');
        const previousSeances = JSON.parse(
            document.getElementById('previous-seances-data').textContent || '[]'
        );
        const typeCategoryMap = JSON.parse(
            document.getElementById('type-category-data').textContent || '{}'
        );
        let exerciceIndex = 0;
        let cachedOptions = [];

        function buildOptions(options) {
            return options
                .map(option => `<option value="${option.id}">${option.nom}</option>`)
                .join('');
        }

        async function fetchExercices(type) {
            if (!type) {
                cachedOptions = [];
                return;
            }

            const response = await fetch(`{{ route('exercices.index') }}?type=${encodeURIComponent(type)}`);
            if (!response.ok) {
                cachedOptions = [];
                return;
            }

            cachedOptions = await response.json();
        }

        function refreshExerciceSelects() {
            const optionsHtml = buildOptions(cachedOptions);
            exercicesContainer.querySelectorAll('select[name$="[exercice_id]"]').forEach(select => {
                const selected = select.value;
                select.innerHTML = '<option value="" disabled selected>Choisir un exercice</option>' + optionsHtml;
                if (selected) {
                    select.value = selected;
                }
            });
        }

        function addExerciceBlock(values, options = {}) {
            const html = template.innerHTML.replaceAll('__INDEX__', String(exerciceIndex));
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            const block = wrapper.firstElementChild;
            const select = block.querySelector('select');
            const optionsHtml = buildOptions(cachedOptions);

            select.innerHTML = '<option value="" disabled selected>Choisir un exercice</option>' + optionsHtml;
            if (values?.exercice_id) {
                select.value = String(values.exercice_id);
            }

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

            block.querySelector('.remove-exercice').addEventListener('click', () => {
                block.remove();
            });

            if (options.prepend) {
                exercicesContainer.prepend(block);
            } else {
                exercicesContainer.appendChild(block);
            }

            refreshExerciceSelects();
            exerciceIndex += 1;
        }

        function findLatestSeance(typeCode) {
            if (!typeCode) {
                return null;
            }

            return previousSeances.find(seance => seance.type_code === typeCode) ?? null;
        }

        function isCardioType(typeCode) {
            if (!typeCode) {
                return false;
            }

            const categoryName = (typeCategoryMap[typeCode] || '').toLowerCase();
            return categoryName === 'cardio';
        }

        function toggleSections(isCardio, hasType) {
            if (!hasType) {
                musculationSection.classList.add('d-none');
                cardioSection.classList.add('d-none');
                durationInput?.removeAttribute('required');
                caloriesInput?.removeAttribute('required');
                return;
            }

            if (isCardio) {
                musculationSection.classList.add('d-none');
                cardioSection.classList.remove('d-none');
                durationInput?.setAttribute('required', 'required');
                caloriesInput?.setAttribute('required', 'required');
            } else {
                musculationSection.classList.remove('d-none');
                cardioSection.classList.add('d-none');
                durationInput?.removeAttribute('required');
                caloriesInput?.removeAttribute('required');
            }
        }

        function loadLatestSeance(typeCode) {
            exercicesContainer.innerHTML = '';
            exerciceIndex = 0;

            if (!typeCode) {
                toggleSections(false, false);
                return;
            }

            const latest = findLatestSeance(typeCode);
            const cardio = isCardioType(typeCode);
            toggleSections(cardio, true);

            if (cardio) {
                if (latest) {
                    if (durationInput) {
                        durationInput.value = latest.duration_minutes ?? '';
                    }
                    if (caloriesInput) {
                        caloriesInput.value = latest.calories ?? '';
                    }
                } else {
                    if (durationInput) {
                        durationInput.value = '';
                    }
                    if (caloriesInput) {
                        caloriesInput.value = '';
                    }
                }
                return;
            }

            if (latest && latest.exercices.length) {
                latest.exercices.forEach(item => addExerciceBlock(item));
                return;
            }

            addExerciceBlock();
        }

        typeSelect.addEventListener('change', async () => {
            await fetchExercices(typeSelect.value);
            loadLatestSeance(typeSelect.value);
        });

        addExerciceButton.addEventListener('click', () => addExerciceBlock(null, { prepend: true }));
    })();
</script>

<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<script>
    (function () {
        const chartData = JSON.parse(document.getElementById('activity-chart-data').textContent || '[]');
        const modeSelect = document.getElementById('activity-chart-mode');
        const seriesSelect = document.getElementById('activity-chart-series');
        const chartContainer = document.getElementById('activity-chart');
        const dateStartInput = document.getElementById('activity-date-start');
        const dateEndInput = document.getElementById('activity-date-end');

        function toDate(value) {
            return new Date(value);
        }

        function formatLabel(value) {
            return value || '—';
        }

        function computeSeanceCharge(seance) {
            return (seance.exercices || []).reduce((total, exercice) => {
                const series = exercice.series || [];
                const exerciceCharge = series.reduce((sum, serie) => {
                    if (!serie.done) {
                        return sum;
                    }
                    const reps = Number(serie.reps || 0);
                    const poids = Number(serie.poids || 0);
                    return sum + reps * poids;
                }, 0);
                return total + exerciceCharge;
            }, 0);
        }

        function aggregateByDate(entries) {
            const map = new Map();
            entries.forEach(entry => {
                const key = entry.date;
                map.set(key, (map.get(key) || 0) + entry.value);
            });
            return Array.from(map.entries())
                .map(([date, value]) => ({ date, value }))
                .sort((a, b) => toDate(a.date) - toDate(b.date));
        }

        function withinDateRange(date) {
            if (!dateStartInput?.value && !dateEndInput?.value) {
                return true;
            }

            const current = toDate(date);
            if (dateStartInput?.value) {
                const start = toDate(dateStartInput.value);
                if (current < start) {
                    return false;
                }
            }
            if (dateEndInput?.value) {
                const end = toDate(dateEndInput.value);
                if (current > end) {
                    return false;
                }
            }
            return true;
        }

        function getSeanceTypeSeries() {
            const typeMap = new Map();
            chartData.forEach(seance => {
                if (seance.is_cardio) {
                    return;
                }
                if (!withinDateRange(seance.date)) {
                    return;
                }
                const label = formatLabel(seance.type);
                const charge = computeSeanceCharge(seance);
                if (!typeMap.has(label)) {
                    typeMap.set(label, []);
                }
                typeMap.get(label).push({ date: seance.date, value: charge });
            });

            return Array.from(typeMap.entries()).map(([label, entries]) => ({
                label,
                points: aggregateByDate(entries)
            }));
        }

        function getExerciceSeries() {
            const exerciceMap = new Map();
            chartData.forEach(seance => {
                if (seance.is_cardio) {
                    return;
                }
                if (!withinDateRange(seance.date)) {
                    return;
                }
                (seance.exercices || []).forEach(exercice => {
                    const label = formatLabel(exercice.nom);
                    const charge = (exercice.series || []).reduce((sum, serie) => {
                        if (!serie.done) {
                            return sum;
                        }
                        return sum + Number(serie.reps || 0) * Number(serie.poids || 0);
                    }, 0);
                    if (!exerciceMap.has(label)) {
                        exerciceMap.set(label, []);
                    }
                    exerciceMap.get(label).push({ date: seance.date, value: charge });
                });
            });

            return Array.from(exerciceMap.entries()).map(([label, entries]) => ({
                label,
                points: aggregateByDate(entries)
            }));
        }

        function getCardioSeries(field) {
            const entries = chartData
                .filter(seance => seance.is_cardio)
                .filter(seance => withinDateRange(seance.date))
                .map(seance => ({
                    date: seance.date,
                    value: Number(seance[field] || 0)
                }))
                .filter(entry => entry.value > 0);
            return [{ label: field === 'calories' ? 'Calories' : 'Durée (min)', points: aggregateByDate(entries) }];
        }

        function buildSeriesOptions(series, preferredLabel) {
            const labels = series.map(item => item.label);
            const currentOptions = Array.from(seriesSelect.options).map(option => option.value);
            const hasSameOptions = labels.length === currentOptions.length
                && labels.every((label, index) => label === currentOptions[index]);

            if (hasSameOptions) {
                if (preferredLabel && labels.includes(preferredLabel)) {
                    seriesSelect.value = preferredLabel;
                }
                return;
            }

            seriesSelect.innerHTML = '';
            series.forEach(item => {
                const option = document.createElement('option');
                option.value = item.label;
                option.textContent = item.label;
                if (preferredLabel && item.label === preferredLabel) {
                    option.selected = true;
                }
                seriesSelect.appendChild(option);
            });

            if (series.length && !seriesSelect.value) {
                seriesSelect.value = series[0].label;
            }
        }

        function renderChart() {
            if (!chartData.length) {
                chartContainer.innerHTML = '<div class="text-muted">Aucune donnée disponible.</div>';
                return;
            }

            const mode = modeSelect.value;
            let series = [];
            let title = '';
            let yTitle = '';
            let multiSeries = false;

            if (mode === 'seance_type') {
                series = getSeanceTypeSeries();
                title = 'Charge totale par type de séance';
                yTitle = 'Charge (kg)';
                multiSeries = true;
            } else if (mode === 'exercice') {
                series = getExerciceSeries();
                title = 'Charge par exercice';
                yTitle = 'Charge (kg)';
                multiSeries = true;
            } else if (mode === 'cardio_calories') {
                series = getCardioSeries('calories');
                title = 'Cardio - calories';
                yTitle = 'Calories';
            } else {
                series = getCardioSeries('duration_minutes');
                title = 'Cardio - durée';
                yTitle = 'Durée (min)';
            }

            if (multiSeries) {
                const previousSelection = seriesSelect.value;
                seriesSelect.classList.remove('d-none');
                buildSeriesOptions(series, previousSelection);
            } else {
                seriesSelect.classList.add('d-none');
            }

            const selectedLabel = seriesSelect.value || series[0]?.label;
            const filteredSeries = multiSeries
                ? series.filter(item => item.label === selectedLabel)
                : series;

            const palette = mode === 'cardio_calories'
                ? ['#fb7185']
                : mode === 'cardio_duration'
                    ? ['#f97316']
                    : ['#ff6b6b', '#f97316', '#facc15', '#fb7185', '#f43f5e'];

            const traces = filteredSeries.map((item, index) => ({
                x: item.points.map(point => point.date),
                y: item.points.map(point => point.value),
                name: item.label,
                mode: 'lines+markers',
                type: 'scatter',
                connectgaps: true,
                line: {
                    color: palette[index % palette.length],
                    width: 2.6
                },
                marker: {
                    color: palette[index % palette.length],
                    size: 6
                }
            }));

            const layout = {
                title,
                dragmode: 'zoom',
                margin: { t: 40, l: 50, r: 40, b: 40 },
                paper_bgcolor: 'rgba(0,0,0,0)',
                plot_bgcolor: 'rgba(0,0,0,0)',
                font: { color: '#f8eaea' },
                xaxis: {
                    type: 'date',
                    gridcolor: 'rgba(248, 113, 113, 0.18)',
                    zerolinecolor: 'rgba(248, 113, 113, 0.18)'
                },
                yaxis: {
                    title: yTitle,
                    gridcolor: 'rgba(248, 113, 113, 0.18)',
                    zerolinecolor: 'rgba(248, 113, 113, 0.18)'
                },
                legend: {
                    orientation: 'h',
                    y: -0.2
                }
            };

            Plotly.newPlot(chartContainer, traces, layout, { responsive: true });
        }

        function setDefaultDateRange() {
            const today = new Date();
            const end = today.toISOString().slice(0, 10);
            const startDate = new Date(today);
            startDate.setFullYear(today.getFullYear() - 1);
            const start = startDate.toISOString().slice(0, 10);
            if (dateStartInput && !dateStartInput.value) {
                dateStartInput.value = start;
            }
            if (dateEndInput && !dateEndInput.value) {
                dateEndInput.value = end;
            }
        }

        modeSelect.addEventListener('change', renderChart);
        seriesSelect.addEventListener('change', renderChart);
        dateStartInput?.addEventListener('change', renderChart);
        dateEndInput?.addEventListener('change', renderChart);
        setDefaultDateRange();
        renderChart();
    })();
</script>
@endsection
