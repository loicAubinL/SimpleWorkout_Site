@extends('layouts.app')

@section('title', __('interface.my_workouts'))

@section('content')
<div class="entete-page">
    <div>
        <span class="surtitre">{{ __('interface.library') }}</span>
        <h1>{{ __('interface.my_workouts') }}</h1>
        <p class="sous-titre">{{ __('interface.my_workouts_text') }}</p>
    </div>
    <a href="{{ route('workout-splits.create') }}" class="bouton">
        <x-icon name="plus" :size="18" /> {{ __('interface.create_workout') }}
    </a>
</div>

<div class="recherche">
    <label for="search" class="sr-only">{{ __('interface.search') }}</label>
    <div class="recherche-barre">
        <div class="champ-icone">
            <x-icon name="search" :size="18" />
            <input id="search" type="search" autocomplete="off"
                placeholder="{{ __('interface.search_placeholder') }}"
                data-search-url="{{ route('recherche.workouts') }}"
                data-searching="{{ __('interface.searching') }}"
                data-empty="{{ __('interface.no_results') }}"
                data-count="{{ __('interface.results') }}"
                data-error="{{ __('interface.search_error') }}">
        </div>
    </div>
    <p id="search-status" role="status" aria-live="polite"></p>
    <ul id="search-results"></ul>
</div>

@if ($splits->isEmpty())
<x-etat-vide>
    <p>{{ __('interface.no_workouts') }}</p>
    <a href="{{ route('workout-splits.create') }}" class="bouton">{{ __('interface.create_first_workout') }}</a>
</x-etat-vide>
@else
<div class="grille">
    @foreach ($splits as $split)
    <x-carte-split :split="$split" />
    @endforeach
</div>
@endif

{{ $splits->links('pagination::simple-default') }}
@endsection

@push('scripts')
<script src="{{ asset('js/recherche.js') }}" defer></script>
@endpush
