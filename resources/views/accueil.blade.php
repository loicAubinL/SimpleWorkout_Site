@extends('layouts.app')

@section('title', __('interface.nav_home'))

@php use App\Support\Formatage; @endphp

@section('content')
@auth
{{-- ===== Membre : workout du jour ===== --}}
@if ($actif && $jourDuJour)
<section class="hero" aria-labelledby="titre-jour">
    <span class="surtitre">
        {{ __('interface.today') }} · {{ __('interface.day_x_of_y', ['x' => $actif->workoutDays->search(fn ($j) => $j->is($jourDuJour)) + 1, 'y' => $actif->workoutDays->count()]) }}
    </span>
    <h1 id="titre-jour">{{ $jourDuJour->name }}</h1>
    <p class="sous-titre">
        <a href="{{ route('workout-splits.show', $actif) }}">{{ $actif->name }}</a>
        @if ($faitAujourdhui)
        · <span class="badge badge-contour"><x-icon name="check" :size="13" /> {{ __('interface.session_started') }}</span>
        @endif
    </p>

    @if ($jourDuJour->exercises->isEmpty())
    <p class="jour-repos"><x-icon name="moon" /> {{ $jourDuJour->is_rest_day ? __('interface.rest_day_today') : __('interface.no_exercises') }}</p>
    @if ($jourDuJour->is_rest_day)
    <form method="post" action="{{ route('session.finish', $actif) }}">
        @csrf
        <input type="hidden" name="jour" value="{{ $jourDuJour->id }}">
        <button type="submit" class="bouton bouton-secondaire"><x-icon name="check" :size="18" /> {{ __('interface.rest_done') }}</button>
    </form>
    @endif
    @else
    <ul class="hero-exercices">
        @foreach ($jourDuJour->exercises as $exercice)
        <li>
            <span class="exercice-nom">{{ $exercice->name }}</span>
            <span class="volume">{{ $exercice->target_sets }} × {{ $exercice->target_reps }}</span>
            @if ($exercice->weight !== null)
            <span class="texte-discret petit">· {{ Formatage::charge($exercice->weight) }}</span>
            @endif
        </li>
        @endforeach
    </ul>
    <div class="actions">
        <a href="{{ route('session.show', $actif) }}" class="bouton">
            <x-icon name="play" :size="18" /> {{ $faitAujourdhui ? __('interface.continue_session') : __('interface.start_session') }}
        </a>
        <a href="{{ route('workout-splits.show', $actif) }}" class="bouton bouton-secondaire">{{ __('interface.see_split') }}</a>
    </div>
    @endif
</section>
@elseif ($actif)
<section class="hero">
    <span class="surtitre">{{ __('interface.today') }}</span>
    <h1>{{ $actif->name }}</h1>
    <p class="sous-titre">{{ __('interface.active_split_no_days') }}</p>
    <a href="{{ route('workout-splits.days.create', $actif) }}" class="bouton"><x-icon name="plus" :size="18" /> {{ __('interface.add_day') }}</a>
</section>
@else
<section class="hero">
    <span class="surtitre">{{ __('interface.hello', ['name' => auth()->user()->name]) }}</span>
    <h1>{{ __('interface.no_active_title') }}</h1>
    <p class="sous-titre">{{ __('interface.no_active_workout') }}</p>
    <div class="actions">
        <a href="{{ route('workout-splits.index') }}" class="bouton"><x-icon name="dumbbell" :size="18" /> {{ __('interface.choose_active') }}</a>
        <a href="{{ route('workouts.publics') }}" class="bouton bouton-secondaire">{{ __('interface.browse_public') }}</a>
    </div>
</section>
@endif

<div class="stats section">
    <div class="carte stat">
        <span class="stat-valeur">{{ $stats['semaine'] }}</span>
        <span class="stat-libelle">{{ __('interface.sessions_this_week') }}</span>
    </div>
    <div class="carte stat">
        <span class="stat-valeur">{{ $stats['derniere'] ? \Illuminate\Support\Carbon::parse($stats['derniere'])->locale(app()->getLocale())->diffForHumans(short: true) : '—' }}</span>
        <span class="stat-libelle">{{ __('interface.last_session') }}</span>
    </div>
    <a href="{{ route('workout-splits.index') }}" class="carte stat lien-carte">
        <span class="stat-valeur">{{ $stats['workouts'] }}</span>
        <span class="stat-libelle">{{ __('interface.my_workouts') }}</span>
    </a>
    <a href="{{ route('saved.index') }}" class="carte stat lien-carte">
        <span class="stat-valeur">{{ $stats['sauvegardes'] }}</span>
        <span class="stat-libelle">{{ __('interface.nav_saved') }}</span>
    </a>
</div>
@else
{{-- ===== Visiteur ===== --}}
<section class="hero accueil-visiteur">
    <span class="surtitre">SimpleWorkout</span>
    <h1>{!! __('interface.guest_title_html') !!}</h1>
    <p class="sous-titre">{{ __('interface.welcome_text') }}</p>
    <div class="actions">
        <a href="{{ route('register') }}" class="bouton"><x-icon name="user-plus" :size="18" /> {{ __('interface.register_title') }}</a>
        <a href="{{ route('workouts.publics') }}" class="bouton bouton-secondaire">{{ __('interface.browse_public') }}</a>
    </div>
</section>

<div class="atouts section">
    <div class="carte atout">
        <x-icon name="calendar" :size="28" />
        <h3>{{ __('interface.feature_plan_title') }}</h3>
        <p>{{ __('interface.feature_plan_text') }}</p>
    </div>
    <div class="carte atout">
        <x-icon name="list-checks" :size="28" />
        <h3>{{ __('interface.feature_log_title') }}</h3>
        <p>{{ __('interface.feature_log_text') }}</p>
    </div>
    <div class="carte atout">
        <x-icon name="trending-up" :size="28" />
        <h3>{{ __('interface.feature_progress_title') }}</h3>
        <p>{{ __('interface.feature_progress_text') }}</p>
    </div>
</div>
@endauth

@if ($derniers->isNotEmpty())
<section class="section">
    <div class="section-titre">
        <h2>{{ __('interface.latest_public') }}</h2>
        <a href="{{ route('workouts.publics') }}" class="bouton bouton-fantome bouton-petit">{{ __('interface.see_all_public') }} <x-icon name="chevron-right" :size="16" /></a>
    </div>
    <div class="grille">
        @foreach ($derniers as $split)
        <x-carte-split :split="$split" avec-auteur />
        @endforeach
    </div>
</section>
@endif
@endsection
