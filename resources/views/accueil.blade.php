@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<h1>Bienvenue sur SimpleWorkout</h1>
<p>Gère tes programmes d'entraînement et suis ta progression.</p>

<h2>Workout actif</h2>
@if ($actif)
<ul>
    <li>
        <a href="{{ route('workout-splits.show', $actif) }}">
            {{ $actif->name }}
            <span class="badge">{{ $actif->workout_days_count }} jour(s)</span>
        </a>
    </li>
</ul>
@else
<p class="etat-vide">Aucun workout actif pour l'instant.</p>
@endif

<a href="{{ route('workout-splits.index') }}" class="bouton">Voir mes workouts</a>
@endsection