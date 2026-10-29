@extends('layouts.app')

@section('title', __('interface.my_workouts'))

@section('content')
<h1>{{ __('interface.my_workouts') }}</h1>
<a href="{{ route('workout-splits.create') }}" class="bouton">{{ __('interface.create_workout') }}</a>

<div class="recherche">
    <label for="search">{{ __('interface.search') }}</label>
    <input id="search" type="search" autocomplete="off"
        placeholder="{{ __('interface.search_placeholder') }}"
        data-search-url="{{ route('recherche.workouts') }}"
        data-searching="{{ __('interface.searching') }}"
        data-empty="{{ __('interface.no_results') }}"
        data-count="{{ __('interface.results') }}"
        data-error="{{ __('interface.search_error') }}">
    <p id="search-status" role="status" aria-live="polite"></p>
    <ul id="search-results"></ul>
</div>

@if ($splits->isEmpty())
<p class="etat-vide">{{ __('interface.no_workouts') }}</p>
@else
<ul>
    @foreach ($splits as $split)
    <li>
        <a href="{{ route('workout-splits.show', $split) }}">
            {{ $split->name }}
            <span class="badge">
                {{ __('interface.days_count', ['count' => $split->workout_days_count]) }}{{ $split->is_active ? ' · ' . __('interface.active') : '' }}
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