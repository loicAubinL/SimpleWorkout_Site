@extends('layouts.app')

@section('title', __('interface.nav_home'))

@section('content')
<h1>{{ __('interface.welcome_title') }}</h1>
<p>{{ __('interface.welcome_text') }}</p>

@auth
<h2>{{ __('interface.active_workout') }}</h2>
@if ($actif)
<ul>
    <li>
        <a href="{{ route('workout-splits.show', $actif) }}">
            {{ $actif->name }}
            <span class="badge">{{ __('interface.days_count', ['count' => $actif->workout_days_count]) }}</span>
        </a>
    </li>
</ul>
@else
<p class="etat-vide">{{ __('interface.no_active_workout') }}</p>
@endif
@endauth

<h2>{{ __('interface.latest_public') }}</h2>
<div class="grille">
    @foreach ($derniers as $split)
    <x-carte-split :split="$split" avec-auteur />
    @endforeach
</div>
<a href="{{ route('workouts.publics') }}" class="bouton">{{ __('interface.see_all_public') }}</a>
@endsection