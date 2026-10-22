@extends('layouts.app')

@section('title', 'Mes workouts')

@section('content')
<h1>Mes workouts</h1>
<a href="{{ route('workout-splits.create') }}" class="bouton">+ Créer un workout</a>
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