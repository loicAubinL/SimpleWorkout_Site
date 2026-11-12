@extends('layouts.app')

@section('title', __('interface.saved_title'))

@section('content')
<div class="entete-page">
    <div>
        <span class="surtitre">{{ __('interface.library') }}</span>
        <h1>{{ __('interface.saved_title') }}</h1>
        <p class="sous-titre">{{ __('interface.saved_text') }}</p>
    </div>
    <a href="{{ route('workouts.publics') }}" class="bouton bouton-secondaire"><x-icon name="globe" :size="18" /> {{ __('interface.browse_public') }}</a>
</div>

@if ($splits->isEmpty())
<x-etat-vide icone="bookmark">
    <p>{{ __('interface.no_saved') }}</p>
    <a href="{{ route('workouts.publics') }}" class="bouton">{{ __('interface.browse_public') }}</a>
</x-etat-vide>
@else
<div class="grille">
    @foreach ($splits as $split)
    <x-carte-split :split="$split" avec-auteur>
        <x-slot:actions>
            <form method="post" action="{{ route('workout-splits.copy', $split) }}">
                @csrf
                <button type="submit" class="bouton bouton-petit"><x-icon name="copy" :size="16" /> {{ __('interface.copy') }}<span class="sr-only"> : {{ $split->name }}</span></button>
            </form>
            <x-bouton-sauvegarde :split="$split" :sauvegarde="true" />
        </x-slot:actions>
    </x-carte-split>
    @endforeach
</div>
@endif

{{ $splits->links('pagination::simple-default') }}
@endsection
