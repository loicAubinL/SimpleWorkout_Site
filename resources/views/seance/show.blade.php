@extends('layouts.app')

@section('title', __('interface.session') . ' · ' . $jour->name)

@php use App\Support\Formatage; @endphp

@section('content')
<a href="{{ route('workout-splits.show', $split) }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ $split->name }}</a>

<div class="entete-page">
    <div>
        <span class="surtitre">{{ __('interface.session') }} · {{ now()->locale(app()->getLocale())->isoFormat('dddd D MMMM') }}</span>
        <h1>{{ $jour->name }}</h1>
    </div>

    {{-- Choisir un autre jour du split (GET, sans JavaScript obligatoire) --}}
    <form method="get" action="{{ route('session.show', $split) }}" class="actions">
        <label for="jour" class="sr-only">{{ __('interface.change_day') }}</label>
        <select id="jour" name="jour" onchange="this.form.submit()">
            @foreach ($split->workoutDays as $j)
            <option value="{{ $j->id }}" @selected($j->is($jour))>{{ $loop->iteration }}. {{ $j->name }}</option>
            @endforeach
        </select>
        <noscript><button type="submit" class="bouton bouton-secondaire bouton-petit">{{ __('interface.apply') }}</button></noscript>
    </form>
</div>

@if ($errors->any())
<div class="message message-alerte" role="alert">
    <x-icon name="x" />
    <span>{{ $errors->first() }}</span>
</div>
@endif

@if ($jour->exercises->isEmpty())
<x-etat-vide icone="moon">
    <p>{{ $jour->is_rest_day ? __('interface.rest_day_today') : __('interface.no_exercises') }}</p>
</x-etat-vide>
@else
@php
    $totalPrevu = $jour->exercises->sum('target_sets');
    $totalFait = $seriesDuJour->flatten()->count();
@endphp
<div class="carte bloc-progression" aria-live="polite">
    <div class="carte-titre">
        <span class="texte-discret">{{ __('interface.sets_done', ['done' => $totalFait, 'total' => $totalPrevu]) }}</span>
        <span class="texte-discret petit">{{ min(100, $totalPrevu ? round($totalFait / $totalPrevu * 100) : 0) }} %</span>
    </div>
    <div class="barre-progression" role="progressbar" aria-valuemin="0" aria-valuemax="{{ $totalPrevu }}" aria-valuenow="{{ $totalFait }}" aria-label="{{ __('interface.progress') }}">
        <span style="width: {{ min(100, $totalPrevu ? $totalFait / $totalPrevu * 100 : 0) }}%"></span>
    </div>
</div>

<div class="pile">
    @foreach ($jour->exercises as $exercice)
    @php
        $faites = $seriesDuJour->get($exercice->id, collect());
        $avant = $precedentes->get($exercice->id);
        $chargeProposee = $faites->last()?->weight ?? $avant?->max('weight') ?? $exercice->weight;
        $termine = $faites->count() >= $exercice->target_sets;
    @endphp
    <section class="carte seance-exercice" id="exercice-{{ $exercice->id }}" aria-labelledby="titre-ex-{{ $exercice->id }}">
        <div class="jour-entete">
            <span @class(['jour-numero', 'badge-accent' => $termine])>
                @if ($termine)<x-icon name="check" :size="16" />@else{{ $loop->iteration }}@endif
            </span>
            <h3 id="titre-ex-{{ $exercice->id }}">{{ $exercice->name }}</h3>
            <x-exercice-chiffres :exercice="$exercice" />
        </div>

        @if ($avant && $avant->isNotEmpty())
        <p class="jour-repos petit">
            <x-icon name="history" :size="15" />
            {{ __('interface.last_time') }} :
            {{ $avant->map(fn ($s) => ($s->weight !== null ? Formatage::charge($s->weight, false) . ' × ' : '') . $s->reps)->join(' · ') }}
            <a href="{{ route('progress.show', $exercice) }}" class="petit">{{ __('interface.see_progress') }}</a>
        </p>
        @endif

        @if ($faites->isNotEmpty())
        <ol class="seance-series">
            @foreach ($faites as $serie)
            <li>
                <span class="numero">{{ $serie->set_number }}</span>
                <span class="valeur">
                    <x-icon name="check" :size="15" />
                    {{ $serie->weight !== null ? Formatage::charge($serie->weight) . ' × ' : '' }}{{ trans_choice('interface.reps_count', $serie->reps) }}
                </span>
                <form method="post" action="{{ route('session.sets.destroy', $serie) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.delete_set') }}">
                        <x-icon name="x" :size="15" /><span class="sr-only">{{ __('interface.delete_set') }} {{ $serie->set_number }}</span>
                    </button>
                </form>
            </li>
            @endforeach
        </ol>
        @endif

        <form method="post" action="{{ route('session.sets.store', $exercice) }}" class="form-serie">
            @csrf
            <div class="champ">
                <label for="weight-{{ $exercice->id }}">{{ __('interface.weight_kg') }}</label>
                <input id="weight-{{ $exercice->id }}" name="weight" type="number" min="0" max="1000" step="0.5" inputmode="decimal"
                    value="{{ $chargeProposee !== null ? (float) $chargeProposee : '' }}">
            </div>
            <div class="champ">
                <label for="reps-{{ $exercice->id }}">{{ __('interface.reps') }}</label>
                <input id="reps-{{ $exercice->id }}" name="reps" type="number" min="1" max="200" inputmode="numeric" required
                    value="{{ $faites->last()?->reps ?? (int) $exercice->target_reps ?: '' }}">
            </div>
            <button type="submit" class="bouton"><x-icon name="plus" :size="18" /> {{ __('interface.log_set') }}</button>
        </form>
    </section>
    @endforeach
</div>
@endif

<div class="carte actions section">
    <p class="texte-discret barre-outils-titre">{{ __('interface.finish_hint') }}</p>
    <form method="post" action="{{ route('session.finish', $split) }}">
        @csrf
        <input type="hidden" name="jour" value="{{ $jour->id }}">
        <button type="submit" class="bouton"><x-icon name="circle-check" :size="18" /> {{ __('interface.finish_session') }}</button>
    </form>
</div>
@endsection
