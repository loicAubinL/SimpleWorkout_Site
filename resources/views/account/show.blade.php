@extends('layouts.app')

@section('title', __('interface.my_account'))

@section('content')
<h1>{{ __('interface.my_account') }}</h1>
<p>{{ $user->name }} · {{ $user->email }}</p>

<h2>{{ __('interface.active_workout') }}</h2>
@if ($actif)
<ul>
    <li><a href="{{ route('workout-splits.show', $actif) }}">{{ $actif->name }}</a></li>
</ul>
@else
<p class="etat-vide">{{ __('interface.no_active_workout') }}</p>
@endif

<h2>{{ __('interface.my_workouts') }}</h2>
@if ($splits->isEmpty())
<p class="etat-vide">{{ __('interface.no_workouts') }}</p>
@else
<ul>
    @foreach ($splits as $split)
    <li>
        <a href="{{ route('workout-splits.show', $split) }}">
            {{ $split->name }}
            <span class="badge">{{ __('interface.days_count', ['count' => $split->workout_days_count]) }}</span>
        </a>
    </li>
    @endforeach
</ul>
@endif
@endsection