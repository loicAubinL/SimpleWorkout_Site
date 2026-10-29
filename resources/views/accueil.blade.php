@extends('layouts.app')

@section('title', __('interface.nav_home'))

@section('content')
<h1>{{ __('interface.welcome_title') }}</h1>
<p>{{ __('interface.welcome_text') }}</p>

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

<a href="{{ route('workout-splits.index') }}" class="bouton">{{ __('interface.see_workouts') }}</a>
@endsection