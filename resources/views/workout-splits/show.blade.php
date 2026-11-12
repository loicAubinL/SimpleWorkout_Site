@extends('layouts.app')

@section('title', $split->name)

@php
    $estProprietaire = auth()->id() === $split->user_id;
    $peutModifier = auth()->user()?->can('update', $split) ?? false;
    $nbExercices = $split->workoutDays->sum(fn ($jour) => $jour->exercises->count());
@endphp

@section('content')
@if ($estProprietaire)
<a href="{{ route('workout-splits.index') }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ __('interface.my_workouts') }}</a>
@elseif (auth()->user()?->isManager())
<a href="{{ route('admin.users.show', $split->user_id) }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ $split->user->name }}</a>
@else
<a href="{{ route('workouts.publics') }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ __('interface.public_workouts') }}</a>
@endif

<div class="entete-page">
    <div>
        <div class="meta meta-haut">
            @if ($split->is_active)
            <span class="badge badge-accent"><x-icon name="flame" :size="13" /> {{ __('interface.active') }}</span>
            @endif
            @if ($split->is_public)
            <span class="badge"><x-icon name="globe" :size="13" /> {{ __('interface.public') }}</span>
            @else
            <span class="badge"><x-icon name="lock" :size="13" /> {{ __('interface.private') }}</span>
            @endif
        </div>
        <h1>{{ $split->name }}</h1>
        <p class="sous-titre">
            {{ __('interface.by', ['name' => $split->user->name]) }}
            @if ($split->description)
            <br>{{ $split->description }}
            @endif
        </p>
    </div>

    <div class="actions">
        @can('train', $split)
        @if (Route::has('session.show') && $nbExercices > 0)
        <a href="{{ route('session.show', $split) }}" class="bouton"><x-icon name="play" :size="18" /> {{ __('interface.start_session') }}</a>
        @endif
        @endcan

        <x-bouton-sauvegarde :split="$split" :sauvegarde="$estSauvegarde" />

        @auth
        @can('copy', $split)
        <form method="post" action="{{ route('workout-splits.copy', $split) }}">
            @csrf
            <button type="submit" class="bouton bouton-secondaire bouton-petit"><x-icon name="copy" :size="16" /> {{ __('interface.copy') }}</button>
        </form>
        @endcan
        @endauth

        @guest
        @if ($split->is_public)
        <a href="{{ route('login') }}" class="bouton bouton-secondaire bouton-petit"><x-icon name="log-in" :size="16" /> {{ __('interface.login_to_save') }}</a>
        @endif
        @endguest
    </div>
</div>

<div class="stats">
    <div class="carte stat">
        <span class="stat-valeur">{{ $split->workoutDays->count() }}</span>
        <span class="stat-libelle">{{ __('interface.days') }}</span>
    </div>
    <div class="carte stat">
        <span class="stat-valeur">{{ $nbExercices }}</span>
        <span class="stat-libelle">{{ __('interface.exercises') }}</span>
    </div>
    <div class="carte stat">
        <span class="stat-valeur">{{ $split->saved_by_count }}</span>
        <span class="stat-libelle">{{ __('interface.saves') }}</span>
    </div>
</div>

@if ($peutModifier)
<div class="carte actions barre-outils">
    <span class="texte-discret petit barre-outils-titre">
        <x-icon name="settings" :size="16" /> {{ $estProprietaire ? __('interface.owner_tools') : __('interface.manager_tools') }}
    </span>
    @if (! $split->is_active && $estProprietaire)
    <form method="post" action="{{ route('workout-splits.activate', $split) }}">
        @csrf
        @method('PATCH')
        <button type="submit" class="bouton bouton-secondaire bouton-petit"><x-icon name="flame" :size="16" /> {{ __('interface.set_active') }}</button>
    </form>
    @endif
    <a href="{{ route('workout-splits.edit', $split) }}" class="bouton bouton-secondaire bouton-petit"><x-icon name="pencil" :size="16" /> {{ __('interface.edit') }}</a>
    <form method="post" action="{{ route('workout-splits.destroy', $split) }}"
        data-confirm="{{ __('interface.confirm_delete') }}" onsubmit="return confirm(this.dataset.confirm)">
        @csrf
        @method('DELETE')
        <button type="submit" class="bouton bouton-danger bouton-petit"><x-icon name="trash-2" :size="16" /> {{ __('interface.delete') }}</button>
    </form>
</div>
@endif

<div class="section-titre">
    <h2>{{ __('interface.days') }}</h2>
    @if ($peutModifier)
    <a href="{{ route('workout-splits.days.create', $split) }}" class="bouton bouton-petit"><x-icon name="plus" :size="16" /> {{ __('interface.add_day') }}</a>
    @endif
</div>

@if ($split->workoutDays->isEmpty())
<x-etat-vide icone="calendar">
    <p>{{ __('interface.no_days') }}</p>
    @if ($peutModifier)
    <a href="{{ route('workout-splits.days.create', $split) }}" class="bouton">{{ __('interface.add_first_day') }}</a>
    @endif
</x-etat-vide>
@else
<div class="pile">
    @foreach ($split->workoutDays as $jour)
    <section class="carte jour" id="jour-{{ $jour->id }}" aria-labelledby="titre-jour-{{ $jour->id }}">
        <div class="jour-entete">
            <span class="jour-numero">{{ $loop->iteration }}</span>
            <h3 id="titre-jour-{{ $jour->id }}">{{ $jour->name }}</h3>
            @if ($estProprietaire && $split->is_active && $jourCourant?->is($jour))
            <span class="badge badge-contour">{{ __('interface.next_up') }}</span>
            @endif
            @if ($jour->is_rest_day)
            <span class="badge"><x-icon name="moon" :size="13" /> {{ __('interface.rest_day') }}</span>
            @endif

            @if ($peutModifier)
            <div class="actions-ligne">
                @unless ($loop->first)
                <form method="post" action="{{ route('days.move', $jour) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="direction" value="up">
                    <button type="submit" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.move_up') }}"><x-icon name="arrow-up" :size="16" /><span class="sr-only">{{ __('interface.move_up') }} : {{ $jour->name }}</span></button>
                </form>
                @endunless
                @unless ($loop->last)
                <form method="post" action="{{ route('days.move', $jour) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="direction" value="down">
                    <button type="submit" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.move_down') }}"><x-icon name="arrow-down" :size="16" /><span class="sr-only">{{ __('interface.move_down') }} : {{ $jour->name }}</span></button>
                </form>
                @endunless
                <a href="{{ route('days.edit', $jour) }}" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.edit') }}"><x-icon name="pencil" :size="16" /><span class="sr-only">{{ __('interface.edit') }} : {{ $jour->name }}</span></a>
                <form method="post" action="{{ route('days.destroy', $jour) }}"
                    data-confirm="{{ __('interface.confirm_delete_day') }}" onsubmit="return confirm(this.dataset.confirm)">
                    @csrf @method('DELETE')
                    <button type="submit" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.delete') }}"><x-icon name="trash-2" :size="16" /><span class="sr-only">{{ __('interface.delete') }} : {{ $jour->name }}</span></button>
                </form>
            </div>
            @endif
        </div>

        @if ($jour->exercises->isEmpty())
        <p class="jour-repos">
            @if ($jour->is_rest_day)
            <x-icon name="moon" :size="18" /> {{ __('interface.rest_day_text') }}
            @else
            {{ __('interface.no_exercises') }}
            @endif
        </p>
        @else
        <ol class="exercices">
            @foreach ($jour->exercises as $exercice)
            <li>
                <div>
                    <span class="exercice-nom">{{ $exercice->name }}</span>
                    @if ($exercice->notes)
                    <p class="exercice-notes">{{ $exercice->notes }}</p>
                    @endif
                </div>
                <div class="actions">
                    <x-exercice-chiffres :exercice="$exercice" />
                    @if ($peutModifier)
                    <div class="actions-ligne">
                        @unless ($loop->first)
                        <form method="post" action="{{ route('exercises.move', $exercice) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="direction" value="up">
                            <button type="submit" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.move_up') }}"><x-icon name="arrow-up" :size="15" /><span class="sr-only">{{ __('interface.move_up') }} : {{ $exercice->name }}</span></button>
                        </form>
                        @endunless
                        @unless ($loop->last)
                        <form method="post" action="{{ route('exercises.move', $exercice) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="direction" value="down">
                            <button type="submit" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.move_down') }}"><x-icon name="arrow-down" :size="15" /><span class="sr-only">{{ __('interface.move_down') }} : {{ $exercice->name }}</span></button>
                        </form>
                        @endunless
                        <a href="{{ route('exercises.edit', $exercice) }}" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.edit') }}"><x-icon name="pencil" :size="15" /><span class="sr-only">{{ __('interface.edit') }} : {{ $exercice->name }}</span></a>
                        <form method="post" action="{{ route('exercises.destroy', $exercice) }}"
                            data-confirm="{{ __('interface.confirm_delete_exercise') }}" onsubmit="return confirm(this.dataset.confirm)">
                            @csrf @method('DELETE')
                            <button type="submit" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.delete') }}"><x-icon name="trash-2" :size="15" /><span class="sr-only">{{ __('interface.delete') }} : {{ $exercice->name }}</span></button>
                        </form>
                    </div>
                    @endif
                </div>
            </li>
            @endforeach
        </ol>
        @endif

        @if ($peutModifier)
        <div class="jour-pied">
            <a href="{{ route('days.exercises.create', $jour) }}" class="bouton bouton-fantome bouton-petit"><x-icon name="plus" :size="16" /> {{ __('interface.add_exercise') }}</a>
        </div>
        @endif
    </section>
    @endforeach
</div>
@endif
@endsection
