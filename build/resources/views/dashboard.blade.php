@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Formulaire d'ajout --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Ajouter une donnée</div>
        <div class="card-body">
            <form method="POST" action="{{ route('donnees.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control" required>
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
                        <input type="number" step="0.01" name="depenses" class="form-control" placeholder="Dépenses (€)">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="etiquettes" class="form-control" placeholder="Étiquettes (ex: sport,jeûne)">
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-success">Ajouter</button>
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
                        <button type="submit" class="btn btn-outline-primary">Importer CSV</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tableau de données --}}
    @include('partials.tableau')

    {{-- Graphique --}}
    @include('partials.graphique')
</div>
@include('partials.dashboard-scripts')
@endsection
