@extends('layouts.app')

@section('title', __('interface.public_workouts'))

@section('content')
<div class="entete-page">
    <div>
        <span class="surtitre">{{ __('interface.community') }}</span>
        <h1>{{ __('interface.public_workouts') }}</h1>
        <p class="sous-titre">{{ __('interface.public_workouts_text') }}</p>
    </div>
</div>

<form method="get" action="{{ route('workouts.publics') }}" class="recherche-barre" role="search">
    <label for="q" class="sr-only">{{ __('interface.search_public') }}</label>
    <div class="champ-icone">
        <x-icon name="search" :size="18" />
        <input id="q" name="q" type="search" value="{{ $terme }}" maxlength="100"
            placeholder="{{ __('interface.search_public_placeholder') }}"
            @error('q') aria-invalid="true" @enderror>
    </div>
    <button type="submit" class="bouton">{{ __('interface.search_button') }}</button>
</form>
@error('q') <p class="message-erreur" role="alert">{{ $message }}</p> @enderror

@if ($terme !== '')
<p class="texte-discret" role="status">
    {{ trans_choice('interface.results_for', $splits->total(), ['count' => $splits->total(), 'term' => $terme]) }}
    · <a href="{{ route('workouts.publics') }}">{{ __('interface.clear_search') }}</a>
</p>
@endif

@if ($splits->isEmpty())
<x-etat-vide icone="globe">
    <p>{{ $terme !== '' ? __('interface.no_results') : __('interface.no_public_workouts') }}</p>
</x-etat-vide>
@else
<div class="grille">
    @foreach ($splits as $split)
    <x-carte-split :split="$split" avec-auteur>
        <x-slot:actions>
            <x-bouton-sauvegarde :split="$split" :sauvegarde="in_array($split->id, $sauvegardes, true)" />
            @if ($split->saved_by_count > 0)
            <span class="texte-discret petit"><x-icon name="bookmark" :size="14" /> {{ $split->saved_by_count }}</span>
            @endif
        </x-slot:actions>
    </x-carte-split>
    @endforeach
</div>
@endif

{{ $splits->links('pagination::simple-default') }}
@endsection
