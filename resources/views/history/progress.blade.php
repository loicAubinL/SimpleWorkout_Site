@extends('layouts.app')

@section('title', __('interface.progress') . ' · ' . $exercice->name)

@php
    use App\Support\Formatage;

    // Mesure suivie : la meilleure charge si l'exercice est chargé, sinon les répétitions.
    $avecCharge = $seances->contains(fn ($s) => $s->meilleure_charge !== null);
    $valeurs = $seances->map(fn ($s) => $avecCharge ? (float) $s->meilleure_charge : (float) $s->reps)->values();
    $libelle = $avecCharge ? __('interface.best_weight') : __('interface.best_reps');

    // Géométrie du graphique (SVG fait à la main, un seul axe).
    $l = 640; $h = 240; $g = 44; $d = 16; $hautMarge = 20; $basMarge = 28;
    $min = $valeurs->min(); $max = $valeurs->max();
    // Pas « rond » (1, 2, 2,5, 5 × 10^n) pour environ 4 graduations.
    $brut = (($max - $min) ?: max(1, $max * 0.2)) / 4;
    $ordre = 10 ** floor(log10($brut));
    $pas = collect([1, 2, 2.5, 5, 10])->map(fn ($m) => $m * $ordre)->first(fn ($p) => $p >= $brut);
    $bas = max(0, floor($min / $pas) * $pas);
    $haut = $bas + $pas * (int) ceil(($max - $bas) / $pas + 0.0001);
    $haut = $haut <= $bas ? $bas + $pas : $haut;
    $n = $valeurs->count();
    $x = fn ($i) => $n > 1 ? $g + ($l - $g - $d) * $i / ($n - 1) : ($l + $g - $d) / 2;
    $y = fn ($v) => $hautMarge + ($h - $hautMarge - $basMarge) * (1 - ($v - $bas) / ($haut - $bas));
    $points = $valeurs->map(fn ($v, $i) => round($x($i), 1) . ',' . round($y($v), 1));
    $graduations = collect(range(0, (int) round(($haut - $bas) / $pas)))->map(fn ($k) => $bas + $k * $pas);
    $formater = fn ($v) => $avecCharge ? Formatage::charge($v) : $v;
    $premiere = $seances->first(); $derniere = $seances->last();
@endphp

@section('content')
<a href="{{ route('history.index') }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ __('interface.nav_history') }}</a>

<div class="entete-page">
    <div>
        <span class="surtitre">{{ $exercice->workoutDay->workoutSplit->name }} · {{ $exercice->workoutDay->name }}</span>
        <h1>{{ $exercice->name }}</h1>
    </div>
    @if ($exercice->workoutDay->workoutSplit->is_active)
    <a href="{{ route('session.show', ['workoutSplit' => $exercice->workoutDay->workoutSplit, 'jour' => $exercice->workout_day_id]) }}" class="bouton">
        <x-icon name="play" :size="18" /> {{ __('interface.start_session') }}
    </a>
    @endif
</div>

@if ($seances->isEmpty())
<x-etat-vide icone="trending-up">
    <p>{{ __('interface.no_progress') }}</p>
</x-etat-vide>
@else
<div class="stats">
    <div class="carte stat">
        <span class="stat-valeur">{{ $formater($valeurs->max()) }}</span>
        <span class="stat-libelle">{{ $libelle }} ({{ __('interface.record') }})</span>
    </div>
    @if ($avecCharge)
    <div class="carte stat">
        <span class="stat-valeur">{{ Formatage::charge($seances->max('un_rm')) }}</span>
        <span class="stat-libelle">{{ __('interface.estimated_1rm') }}</span>
    </div>
    @endif
    <div class="carte stat">
        @php $ecart = $valeurs->last() - $valeurs->first(); @endphp
        <span class="stat-valeur">{{ $ecart > 0 ? '+' : '' }}{{ $avecCharge ? Formatage::charge($ecart) : $ecart }}</span>
        <span class="stat-libelle">{{ __('interface.since', ['date' => $premiere->date->locale(app()->getLocale())->isoFormat('D MMM')]) }}</span>
    </div>
    <div class="carte stat">
        <span class="stat-valeur">{{ $seances->count() }}</span>
        <span class="stat-libelle">{{ __('interface.sessions') }}</span>
    </div>
</div>

<figure class="carte" aria-labelledby="legende-graphique">
    <figcaption id="legende-graphique" class="carte-titre">
        <h2>{{ $libelle }}</h2>
        <span class="texte-discret petit">{{ __('interface.per_session') }}</span>
    </figcaption>
    <svg class="graphique" viewBox="0 0 {{ $l }} {{ $h }}" role="img"
        aria-label="{{ __('interface.chart_label', ['metric' => $libelle, 'first' => $formater($valeurs->first()), 'last' => $formater($valeurs->last()), 'count' => $n]) }}">
        @foreach ($graduations as $v)
        <line class="grille-ligne" x1="{{ $g }}" x2="{{ $l - $d }}" y1="{{ round($y($v), 1) }}" y2="{{ round($y($v), 1) }}" />
        <text class="axe-texte" x="{{ $g - 8 }}" y="{{ round($y($v), 1) + 4 }}" text-anchor="end">{{ $avecCharge ? Formatage::charge($v, false) : $v }}</text>
        @endforeach

        @if ($n > 1)
        <polygon class="aire" points="{{ round($x(0), 1) }},{{ $h - $basMarge }} {{ $points->join(' ') }} {{ round($x($n - 1), 1) }},{{ $h - $basMarge }}" />
        <polyline class="courbe" points="{{ $points->join(' ') }}" />
        @endif

        @foreach ($seances as $i => $s)
        <g>
            <title>{{ $s->date->locale(app()->getLocale())->isoFormat('D MMM YYYY') }} : {{ $formater($valeurs[$i]) }}{{ $avecCharge ? ' × ' . $s->reps : '' }}</title>
            <circle cx="{{ round($x($i), 1) }}" cy="{{ round($y($valeurs[$i]), 1) }}" r="12" fill="transparent" />
            <circle class="point" cx="{{ round($x($i), 1) }}" cy="{{ round($y($valeurs[$i]), 1) }}" r="4" />
        </g>
        @endforeach

        {{-- Étiquettes directes : seulement la première et la dernière date, et la dernière valeur. --}}
        <text class="axe-texte" x="{{ round($x(0), 1) }}" y="{{ $h - 8 }}" text-anchor="{{ $n > 1 ? 'start' : 'middle' }}">{{ $premiere->date->locale(app()->getLocale())->isoFormat('D MMM') }}</text>
        @if ($n > 1)
        <text class="axe-texte" x="{{ round($x($n - 1), 1) }}" y="{{ $h - 8 }}" text-anchor="end">{{ $derniere->date->locale(app()->getLocale())->isoFormat('D MMM') }}</text>
        @endif
        <text class="point-titre" x="{{ round($x($n - 1), 1) - 8 }}" y="{{ round($y($valeurs->last()), 1) - 12 }}" text-anchor="end">{{ $formater($valeurs->last()) }}</text>
    </svg>
</figure>

<h2>{{ __('interface.sessions') }}</h2>
<div class="tableau-conteneur">
    <table>
        <caption class="sr-only">{{ __('interface.sessions') }} · {{ $exercice->name }}</caption>
        <thead>
            <tr>
                <th scope="col">{{ __('interface.date') }}</th>
                <th scope="col" class="nombre">{{ __('interface.best_set') }}</th>
                <th scope="col" class="nombre">{{ __('interface.sets') }}</th>
                @if ($avecCharge)
                <th scope="col" class="nombre">{{ __('interface.volume') }}</th>
                <th scope="col" class="nombre">{{ __('interface.estimated_1rm') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($seances->reverse() as $s)
            <tr>
                <td>{{ $s->date->locale(app()->getLocale())->isoFormat('ddd D MMM YYYY') }}</td>
                <td class="nombre">{{ $s->meilleure_charge !== null ? Formatage::charge($s->meilleure_charge) . ' × ' : '' }}{{ $s->reps }}</td>
                <td class="nombre">{{ $s->nb_series }}</td>
                @if ($avecCharge)
                <td class="nombre">{{ Formatage::charge($s->volume) }}</td>
                <td class="nombre">{{ Formatage::charge(round($s->un_rm, 1)) }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
