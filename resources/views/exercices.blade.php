@extends('layouts.app')

@section('content')
<div class="container py-4">
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">Données</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('activite') }}">Activité</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('biologie') }}">Biologie</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('exercices.gestion') }}">Exercices</a>
        </li>
    </ul>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Ajouter une catégorie</h5>
                    <form method="POST" action="{{ route('exercices.categories.store') }}" class="mt-3">
                        @csrf
                        <label for="categorie_nom" class="form-label">Nom</label>
                        <input type="text" id="categorie_nom" name="nom" class="form-control" required>
                        <button type="submit" class="btn btn-primary mt-3">Ajouter</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title">Ajouter un type</h5>
                    <form method="POST" action="{{ route('exercices.types.store') }}" class="mt-3">
                        @csrf
                        <label for="type_categorie" class="form-label">Catégorie</label>
                        <select id="type_categorie" name="categorie_id" class="form-select" required>
                            <option value="" disabled selected>Choisir</option>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                            @endforeach
                        </select>

                        <label for="type_nom" class="form-label mt-3">Nom</label>
                        <input type="text" id="type_nom" name="nom" class="form-control" required>

                        <label for="type_code" class="form-label mt-3">Code (ex: musculation_pull)</label>
                        <input type="text" id="type_code" name="code" class="form-control" required>

                        <button type="submit" class="btn btn-primary mt-3">Ajouter</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title">Ajouter un exercice</h5>
                    <form method="POST" action="{{ route('exercices.store') }}" class="mt-3">
                        @csrf
                        <label for="exercice_type" class="form-label">Type</label>
                        <select id="exercice_type" name="seance_type_id" class="form-select" required>
                            <option value="" disabled selected>Choisir</option>
                            @foreach ($categories as $categorie)
                                <optgroup label="{{ $categorie->nom }}">
                                    @foreach ($categorie->types as $type)
                                        <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>

                        <label for="exercice_nom" class="form-label mt-3">Nom</label>
                        <input type="text" id="exercice_nom" name="nom" class="form-control" required>

                        <button type="submit" class="btn btn-primary mt-3">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            @forelse ($categories as $categorie)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $categorie->nom }}</h5>

                        @forelse ($categorie->types as $type)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-2">
                                    <div>
                                        <strong>{{ $type->nom }}</strong>
                                        <span class="text-muted ms-2">{{ $type->code }}</span>
                                    </div>
                                </div>
                                @if ($type->exercices->isEmpty())
                                    <p class="text-muted mb-0">Aucun exercice pour ce type.</p>
                                @else
                                    <ul class="list-group list-group-flush">
                                        @foreach ($type->exercices as $exercice)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ $exercice->nom }}</span>
                                                <form method="POST" action="{{ route('exercices.destroy', $exercice) }}" onsubmit="return confirm('Supprimer cet exercice ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted mb-0">Aucun type de séance pour cette catégorie.</p>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Aucune catégorie pour l'instant.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
