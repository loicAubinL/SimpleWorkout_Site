@extends('layouts.app')

@section('title', $compte->name)

@section('content')
<a href="{{ route('admin.users.index') }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ __('interface.accounts') }}</a>

<div class="entete-page">
    <div>
        <span class="surtitre">{{ $compte->isManager() ? __('interface.role_manager') : __('interface.role_member') }}</span>
        <h1>{{ $compte->name }}</h1>
        <p class="sous-titre">
            {{ $compte->email }}
            · {{ __('interface.member_since', ['date' => $compte->created_at->locale(app()->getLocale())->isoFormat('LL')]) }}
        </p>
    </div>
    <div class="actions">
        <a href="{{ route('admin.users.edit', $compte) }}" class="bouton bouton-secondaire"><x-icon name="pencil" :size="18" /> {{ __('interface.edit') }}</a>
        @unless ($compte->is(auth()->user()))
        <form method="post" action="{{ route('admin.users.destroy', $compte) }}"
            data-confirm="{{ __('interface.confirm_delete_user', ['name' => $compte->name]) }}" onsubmit="return confirm(this.dataset.confirm)">
            @csrf
            @method('DELETE')
            <button type="submit" class="bouton bouton-danger"><x-icon name="trash-2" :size="18" /> {{ __('interface.delete') }}</button>
        </form>
        @endunless
    </div>
</div>

<div class="stats">
    <div class="carte stat">
        <span class="stat-valeur">{{ $compte->workout_splits_count }}</span>
        <span class="stat-libelle">{{ __('interface.workouts') }}</span>
    </div>
    <div class="carte stat">
        <span class="stat-valeur">{{ $compte->saved_workouts_count }}</span>
        <span class="stat-libelle">{{ __('interface.nav_saved') }}</span>
    </div>
    <div class="carte stat">
        <span class="stat-valeur">{{ $compte->logged_sets_count }}</span>
        <span class="stat-libelle">{{ __('interface.total_sets') }}</span>
    </div>
</div>

<div class="section-titre">
    <h2>{{ __('interface.workouts') }}</h2>
    <a href="{{ route('admin.users.splits.create', $compte) }}" class="bouton bouton-petit"><x-icon name="plus" :size="16" /> {{ __('interface.create_workout') }}</a>
</div>

@if ($splits->isEmpty())
<x-etat-vide><p>{{ __('interface.no_workouts') }}</p></x-etat-vide>
@else
<div class="grille">
    @foreach ($splits as $split)
    <x-carte-split :split="$split">
        <x-slot:actions>
            <a href="{{ route('workout-splits.edit', $split) }}" class="bouton bouton-secondaire bouton-petit"><x-icon name="pencil" :size="16" /> {{ __('interface.edit') }}<span class="sr-only"> : {{ $split->name }}</span></a>
            <form method="post" action="{{ route('workout-splits.destroy', $split) }}"
                data-confirm="{{ __('interface.confirm_delete') }}" onsubmit="return confirm(this.dataset.confirm)">
                @csrf
                @method('DELETE')
                <button type="submit" class="bouton bouton-danger bouton-petit"><x-icon name="trash-2" :size="16" /> {{ __('interface.delete') }}<span class="sr-only"> : {{ $split->name }}</span></button>
            </form>
        </x-slot:actions>
    </x-carte-split>
    @endforeach
</div>
@endif
@endsection
