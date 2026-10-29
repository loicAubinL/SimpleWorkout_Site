@extends('layouts.app')

@section('title', 'Mes workouts')

@section('content')
<h1>Mes workouts</h1>
<a href="{{ route('workout-splits.create') }}" class="bouton">+ Créer un workout</a>

<div class="recherche">
    <label for="search">Rechercher un workout ou un jour</label>
    <input id="search" type="search" autocomplete="off" placeholder="Ex. : Legs, Push Pull Legs…"
        data-search-url="{{ route('recherche.workouts') }}"
        data-searching="Recherche en cours..."
        data-empty="Aucun résultat."
        data-count=":count résultat(s)."
        data-error="Recherche temporairement indisponible.">
    <p id="search-status" role="status" aria-live="polite"></p>
    <ul id="search-results"></ul>
</div>

@if ($splits->isEmpty())
<p class="etat-vide">Aucun workout pour l'instant.</p>
@else
<ul>
    @foreach ($splits as $split)
    <li>
        <a href="{{ route('workout-splits.show', $split) }}">
            {{ $split->name }}
            <span class="badge">
                {{ $split->workout_days_count }} jour(s){{ $split->is_active ? ' · Actif' : '' }}
            </span>
        </a>
    </li>
    @endforeach
</ul>
@endif

{{ $splits->links('pagination::simple-default') }}
@endsection

@push('scripts')
<script src="{{ asset('js/recherche.js') }}" defer></script>
@endpush