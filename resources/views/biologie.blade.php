@extends('layouts.app')

@section('body-class', 'theme-biology')

@section('content')
<div class="biology-page py-4">
    <div class="page-hero mb-4">
        <div class="page-hero__content">
            <div class="eyebrow">Biologie</div>
            <h1 class="page-title">Analyses et tendances</h1>
            <p class="page-subtitle">Regroupe tes parametres biologiques pour un suivi clair.</p>
        </div>
        <div class="page-hero__glow" aria-hidden="true"></div>
    </div>

    <ul class="nav nav-tabs app-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">Données</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('activite') }}">Activité</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('biologie') }}">Biologie</a>
        </li>
    </ul>

    <div class="d-flex flex-column gap-4">
        <div>
            @if (session('success'))
                <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert" id="bio-toast">
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" aria-label="Fermer"></button>
                </div>
            @endif
            <div class="section-card">
                <div class="section-card__body">
                    <form method="POST" action="{{ route('biologie.store') }}" id="bio-form">
                        @csrf
                        <div class="section-card__header mb-3">
                            <div>
                                <h2 class="section-title">Paramètres biologiques</h2>
                                <p class="section-subtitle">Saisis les valeurs mesurées pour une analyse.</p>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <input class="form-control" type="date" id="bio-analysis-date" name="date">
                                <button class="btn btn-outline-secondary" type="button" id="bio-new-analysis">Nouvelle analyse</button>
                                <button class="btn btn-primary btn-glow" type="submit" id="bio-add-analysis">Enregistrer</button>
                            </div>
                        </div>
                        <div id="bio-form-fields" class="d-none">
                            <div class="mb-3">
                                <label class="form-label" for="bio-remarks">Remarques</label>
                                <textarea class="form-control" id="bio-remarks" name="remarques" rows="3" placeholder="Ex: à jeun, fatigue, traitement en cours..."></textarea>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle" id="bio-parameters-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 180px;">Intitulé</th>
                                            <th class="text-end">Ref min</th>
                                            <th class="text-end">Ref max</th>
                                            <th class="text-end" style="min-width: 140px;">Mesuré</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($parametres as $parametre)
                                            <tr>
                                                <td>{{ $parametre->label }}</td>
                                                <td class="text-end">{{ $parametre->ref_min ?? '—' }}</td>
                                                <td class="text-end">{{ $parametre->ref_max ?? '—' }}</td>
                                                <td class="text-end">
                                                    <input class="form-control form-control-sm text-end" type="text" inputmode="decimal" name="mesures[{{ $parametre->key }}]" data-param="{{ $parametre->key }}" placeholder="-">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
                                <small class="text-muted">Astuce: utilise la virgule ou le point pour les decimales.</small>
                                <small class="text-muted" id="bio-analyses-count">{{ $analyses->count() ? $analyses->count().' analyse'.($analyses->count() > 1 ? 's' : '').' enregistree'.($analyses->count() > 1 ? 's' : '').'.' : 'Aucune analyse enregistree.' }}</small>
                            </div>
                        </div>
                        <input type="hidden" name="_method" value="POST" id="bio-method">
                    </form>
                </div>
            </div>
            <div class="section-card mt-4">
                <div class="section-card__body">
                    <div class="section-card__header mb-3">
                        <div>
                            <h2 class="section-title">Analyses enregistrees</h2>
                            <p class="section-subtitle">Selectionne une analyse pour modifier ou supprimer.</p>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2" id="bio-analyses-list">
                        @forelse ($analyses as $analyse)
                            <div class="analysis-card d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2" data-analysis-row="{{ $analyse->id }}">
                                <div>
                                    <div class="fw-semibold">{{ $analyse->date->format('d/m/Y') }}</div>
                                    @if ($analyse->remarques)
                                        <div class="text-muted">{{ $analyse->remarques }}</div>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-action="edit" data-analysis-id="{{ $analyse->id }}">Modifier</button>
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-action="delete" data-analysis-id="{{ $analyse->id }}">Supprimer</button>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">Aucune analyse enregistree.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="section-card">
                <div class="section-card__body d-flex flex-column">
                    <div class="section-card__header mb-3">
                        <div>
                            <h2 class="section-title">Evolution sur plusieurs annees</h2>
                            <p class="section-subtitle">Choisis un parametre pour afficher la courbe.</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="section-subtitle" for="bio-parameter-select">Parametre</label>
                            <select class="form-select" id="bio-parameter-select"></select>
                        </div>
                    </div>
                    <div id="bio-chart" class="flex-grow-1"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/json" id="bio-parameters-data">@json($parametresPayload)</script>
<script type="application/json" id="bio-analyses-data">@json($analysesPayload)</script>
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<script>
    (function () {
        const parameters = JSON.parse(document.getElementById('bio-parameters-data').textContent || '[]');

        const analyses = JSON.parse(document.getElementById('bio-analyses-data').textContent || '[]');

        const tableBody = document.querySelector('#bio-parameters-table tbody');
        const dateInput = document.getElementById('bio-analysis-date');
        const form = document.getElementById('bio-form');
        const countLabel = document.getElementById('bio-analyses-count');
        const select = document.getElementById('bio-parameter-select');
        const newButton = document.getElementById('bio-new-analysis');
        const formFields = document.getElementById('bio-form-fields');
        const analysesList = document.getElementById('bio-analyses-list');
        const methodInput = document.getElementById('bio-method');
        const remarksInput = document.getElementById('bio-remarks');
        const chartContainer = document.getElementById('bio-chart');
        const toast = document.getElementById('bio-toast');

        function toNumber(value) {
            if (value === null || value === undefined) {
                return null;
            }
            const cleaned = String(value).trim().replace(',', '.');
            if (!cleaned) {
                return null;
            }
            const parsed = Number(cleaned);
            return Number.isFinite(parsed) ? parsed : null;
        }

        function defaultDate() {
            const today = new Date();
            return today.toISOString().slice(0, 10);
        }

        function renderSelect() {
            select.innerHTML = '';
            parameters.forEach((param, index) => {
                const option = document.createElement('option');
                option.value = param.key;
                option.textContent = param.label;
                if (index === 0) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        }


        function fillInputs(values) {
            tableBody.querySelectorAll('input[data-param]').forEach(input => {
                const key = input.dataset.param;
                input.value = values && values[key] !== undefined && values[key] !== null ? values[key] : '';
            });
        }

        function setFormAction(action, method) {
            form.setAttribute('action', action);
            methodInput.value = method;
        }

        function resetFormAction() {
            setFormAction("{{ route('biologie.store') }}", 'POST');
            formFields.classList.remove('d-none');
        }

        function openNewAnalysis() {
            dateInput.value = defaultDate();
            if (remarksInput) {
                remarksInput.value = '';
            }
            fillInputs({});
            resetFormAction();
            formFields.classList.remove('d-none');
        }

        function openEditAnalysis(analyse) {
            if (!analyse) {
                return;
            }
            dateInput.value = analyse.date;
            if (remarksInput) {
                remarksInput.value = analyse.remarks || '';
            }
            fillInputs(analyse.values || {});
            setFormAction(`{{ url('/biologie') }}/${analyse.id}`, 'PUT');
            formFields.classList.remove('d-none');
        }

        function submitDeleteAnalysis(analyse) {
            if (!analyse) {
                return;
            }
            if (!confirm("Supprimer l'analyse du " + analyse.date + " ?")) {
                return;
            }
            setFormAction(`{{ url('/biologie') }}/${analyse.id}`, 'DELETE');
            form.requestSubmit();
        }

        function getAnalysisValues() {
            const inputs = tableBody.querySelectorAll('input[data-param]');
            const values = {};
            inputs.forEach(input => {
                values[input.dataset.param] = toNumber(input.value);
            });
            return values;
        }

        function getParamByKey(key) {
            return parameters.find(param => param.key === key);
        }

        function renderChart() {
            if (!parameters.length) {
                chartContainer.innerHTML = '<div class="text-muted">Aucun parametre disponible.</div>';
                return;
            }
            const selectedKey = select.value;
            const selectedParam = getParamByKey(selectedKey);
            if (!selectedParam) {
                chartContainer.innerHTML = '<div class="text-muted">Selectionne un parametre.</div>';
                return;
            }
            const points = analyses
                .map(entry => ({
                    date: entry.date,
                    displayDate: entry.displayDate || entry.date,
                    value: entry.values[selectedKey]
                }))
                .filter(entry => entry.value !== null && entry.value !== undefined);

            if (!points.length) {
                chartContainer.innerHTML = '<div class="text-muted">Aucune donnee pour ce parametre.</div>';
                return;
            }

            const sorted = points.sort((a, b) => new Date(a.date) - new Date(b.date));
            const dates = sorted.map(item => item.date);
            const values = sorted.map(item => item.value);
            const labels = sorted.map(item => item.displayDate);

            const traces = [
                {
                    x: dates,
                    y: values,
                    mode: 'lines+markers',
                    name: selectedParam.label,
                    text: labels,
                    hovertemplate: '%{text}<br>%{y}<extra></extra>',
                    line: { color: '#6ee7b7', width: 3 },
                    marker: { size: 7, color: '#34d399' }
                }
            ];

            const lineDates = dates.length === 1 ? [dates[0], dates[0]] : [dates[0], dates[dates.length - 1]];

            if (selectedParam.refMin !== null && selectedParam.refMin !== undefined) {
                traces.push({
                    x: lineDates,
                    y: [selectedParam.refMin, selectedParam.refMin],
                    mode: 'lines',
                    name: 'Ref min',
                    line: { color: '#22c55e', width: 2, dash: 'dash' }
                });
            }

            if (selectedParam.refMax !== null && selectedParam.refMax !== undefined) {
                traces.push({
                    x: lineDates,
                    y: [selectedParam.refMax, selectedParam.refMax],
                    mode: 'lines',
                    name: 'Ref max',
                    line: { color: '#f59e0b', width: 2, dash: 'dash' }
                });
            }

            Plotly.newPlot(chartContainer, traces, {
                margin: { t: 20, r: 20, b: 40, l: 50 },
                height: 380,
                paper_bgcolor: 'rgba(0,0,0,0)',
                plot_bgcolor: 'rgba(0,0,0,0)',
                font: { color: '#e6fff2' },
                xaxis: {
                    title: 'Date',
                    type: 'date',
                    tickformat: '%d/%m/%Y',
                    ticklabelmode: 'period',
                    gridcolor: 'rgba(110, 231, 183, 0.2)',
                    zerolinecolor: 'rgba(110, 231, 183, 0.2)'
                },
                yaxis: {
                    title: selectedParam.label,
                    gridcolor: 'rgba(110, 231, 183, 0.2)',
                    zerolinecolor: 'rgba(110, 231, 183, 0.2)'
                },
                legend: { orientation: 'h', y: 1.12 }
            }, { displayModeBar: false, responsive: true });
        }

        form.addEventListener('submit', event => {
            if (methodInput.value === 'DELETE') {
                return;
            }
            const date = dateInput.value || defaultDate();
            const values = getAnalysisValues();
            const hasValue = Object.values(values).some(value => value !== null && value !== undefined);
            if (!hasValue) {
                alert('Renseigne au moins une valeur mesuree.');
                event.preventDefault();
                return;
            }
            dateInput.value = date;
        });

        newButton.addEventListener('click', () => {
            openNewAnalysis();
        });

        analysesList.addEventListener('click', event => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }
            const action = target.dataset.action;
            const id = Number(target.dataset.analysisId || 0);
            const analyse = analyses.find(item => item.id === id);
            if (action === 'edit') {
                openEditAnalysis(analyse);
                return;
            }
            if (action === 'delete') {
                submitDeleteAnalysis(analyse);
            }
        });

        select.addEventListener('change', renderChart);

        dateInput.value = defaultDate();
        renderSelect();
        formFields.classList.add('d-none');
        renderChart();

        if (toast) {
            const closeButton = toast.querySelector('.btn-close');
            const closeToast = () => toast.remove();
            if (closeButton) {
                closeButton.addEventListener('click', closeToast);
            }
            setTimeout(closeToast, 3500);
        }
    })();
</script>
@endsection
