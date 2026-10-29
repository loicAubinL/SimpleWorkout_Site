@extends('layouts.app')

@section('title', $split->name)

@section('content')
<p><a href="{{ route('workout-splits.index') }}">← Mes workouts</a></p>

<h1>{{ $split->name }}</h1>
<p><span class="badge">{{ $split->is_active ? 'Actif' : 'Inactif' }}</span></p>

<a href="{{ route('workout-splits.edit', $split) }}" class="bouton">Modifier</a>

<form method="post" action="{{ route('workout-splits.destroy', $split) }}"
    onsubmit="return confirm('Supprimer ce workout et tous ses jours ?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="bouton-danger">Supprimer</button>
</form>

<h2>Programme</h2>
@if ($split->program_path)
<a href="{{ Storage::disk('public')->url($split->program_path) }}" download class="bouton">
    Télécharger le programme (JSON)
</a>
@if ($apercu)
<pre class="apercu-json">{{ $apercu }}</pre>
@endif
@else
<p class="etat-vide">Aucun programme joint.</p>
@endif

<h2>Jours</h2>
@forelse ($split->workoutDays as $day)
<div class="carte-exercice">
    <span class="exercice-nom">{{ $day->name }}</span>
    <span class="exercice-details">· {{ $day->is_rest_day ? 'Jour de repos' : 'Entraînement' }}</span>
</div>
@empty
<p class="etat-vide">Aucun jour dans ce workout.</p>
@endforelse
@endsection