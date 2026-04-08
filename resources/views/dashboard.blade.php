@extends('layouts.app')

@section('body-class', 'theme-dashboard')

@section('content')
<div class="dashboard-page">
    <div class="page-hero mb-4">
        <div class="page-hero__content">
            <div class="eyebrow">Tableau de bord</div>
            <h1 class="page-title">Données du quotidien</h1>
            <p class="page-subtitle">Suivi du poids, des macros et des tendances sur une seule vue.</p>
        </div>
        <div class="page-hero__glow" aria-hidden="true"></div>
    </div>

    <ul class="nav nav-tabs app-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dashboard') }}">Données</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('activite') }}">Activité</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('biologie') }}">Biologie</a>
        </li>
    </ul>

    {{-- Formulaire d'ajout --}}
    <section class="section-card mb-4">
        <div class="section-card__header">
            <div>
                <h2 class="section-title">Ajouter une donnée</h2>
                <p class="section-subtitle">Renseigne rapidement tes indicateurs journaliers.</p>
            </div>
        </div>
        <div class="section-card__body">
            <form method="POST" action="{{ route('donnees.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.1" name="poids" class="form-control" placeholder="Poids (kg)">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="calories" class="form-control" placeholder="Calories">
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.1" name="proteines" class="form-control" placeholder="Protéines (g)">
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.1" name="lipides" class="form-control" placeholder="Lipides (g)">
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.1" name="glucides" class="form-control" placeholder="Glucides (g)">
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="depenses" class="form-control" placeholder="Dépenses (Cal)">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="etiquettes" class="form-control" placeholder="Étiquettes (ex: sport,jeûne)">
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary btn-glow">Ajouter</button>
                    </div>
                </div>
            </form>
            <form action="{{ route('donnees.import.csv') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <input type="file" name="csv" accept=".csv" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-light">Importer CSV</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- Graphique --}}
    <div class="section-card mb-4">
        <div class="section-card__header">
            <div>
                <h2 class="section-title">Graphiques clés</h2>
                <p class="section-subtitle">Visualise en un coup d'oeil l'évolution des métriques.</p>
            </div>
        </div>
        <div class="section-card__body">
            @include('partials.graphique')
        </div>
    </div>
    
    {{-- Carte Objectifs de poids --}}

<div class="section-card mb-4">
    <div class="section-card__header">
        <div>
            <h2 class="section-title">Objectifs et seuils</h2>
            <p class="section-subtitle">Cadre ta progression avec un objectif clair.</p>
        </div>
    </div>
    <div class="section-card__body">
        <form id="objectif-form">
        <div class="row g-3">
                <div class="col-md-4">
                    <label for="seuil_calories" class="form-label">Seuil de Calories (Cal)</label>
                    <input type="number" id="seuil_calories" name="seuil_calories" class="form-control" value="2000">
                </div>
         </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="objectif_start" class="form-label">Date de début</label>
                    <input type="date" id="objectif_start" name="objectif_start" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="objectif_end" class="form-label">Date de fin</label>
                    <input type="date" id="objectif_end" name="objectif_end" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="objectif_weight" class="form-label">Poids cible (kg)</label>
                    <input type="number" id="objectif_weight" name="objectif_weight" class="form-control" step="0.1">
                </div>
                <div class="col-12 text-end">
                    <button type="button" onclick="appliquerObjectif()" class="btn btn-primary btn-glow">
                        Appliquer l'objectif
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
    {{-- Tableau de données --}}
    <div class="section-card">
        <div class="section-card__header">
            <div>
                <h2 class="section-title">Historique complet</h2>
                <p class="section-subtitle">Toutes les données enregistrées au même endroit.</p>
            </div>
        </div>
        <div class="section-card__body">
            @include('partials.tableau')
        </div>
    </div>

</div>
<script type="application/json" id="donnees-data">@json($donnees)</script>
@include('partials.dashboard-scripts')
@endsection
