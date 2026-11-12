@extends('layouts.app')

@section('title', __('interface.nav_history'))

@php use App\Support\Formatage; @endphp

@section('content')
<div class="entete-page">
    <div>
        <span class="surtitre">{{ __('interface.tracking') }}</span>
        <h1>{{ __('interface.history_title') }}</h1>
        <p class="sous-titre">{{ __('interface.history_text') }}</p>
    </div>
</div>

<div class="stats">
    <div class="carte stat">
        <span class="stat-valeur">{{ $stats['seances'] }}</span>
        <span class="stat-libelle">{{ __('interface.total_sessions') }}</span>
    </div>
    <div class="carte stat">
        <span class="stat-valeur">{{ $stats['semaine'] }}</span>
        <span class="stat-libelle">{{ __('interface.sessions_this_week') }}</span>
    </div>
    <div class="carte stat">
        <span class="stat-valeur">{{ $stats['series'] }}</span>
        <span class="stat-libelle">{{ __('interface.total_sets') }}</span>
    </div>
</div>

@if ($seances->isEmpty())
<x-etat-vide icone="history">
    <p>{{ __('interface.no_history') }}</p>
    <a href="{{ route('accueil') }}" class="bouton">{{ __('interface.go_today') }}</a>
</x-etat-vide>
@else
<div class="grille-2">
    <section aria-labelledby="titre-seances">
        <h2 id="titre-seances" class="sr-only">{{ __('interface.sessions') }}</h2>
        <div class="pile">
            @foreach ($seances as $seance)
            <article class="carte">
                <div class="carte-titre">
                    <div>
                        <span class="surtitre">{{ $seance->date->locale(app()->getLocale())->isoFormat('dddd D MMMM YYYY') }}</span>
                        <h3>{{ $seance->jours->join(' + ') }}</h3>
                    </div>
                    <div class="meta">
                        <span>{{ trans_choice('interface.sets_count', $seance->nb_series) }}</span>
                        @if ($seance->volume > 0)
                        <span>{{ Formatage::charge($seance->volume) }}</span>
                        @endif
                    </div>
                </div>
                <ul class="liste">
                    @foreach ($seance->exercices as $ligne)
                    <li>
                        <a href="{{ route('progress.show', $ligne->exercice) }}" class="titre principal">{{ $ligne->exercice->name }}</a>
                        <span class="chiffres">
                            @foreach ($ligne->series as $s)
                            <span class="chiffre"><b>{{ $s->weight !== null ? Formatage::charge($s->weight, false) . '×' : '' }}{{ $s->reps }}</b></span>
                            @endforeach
                        </span>
                    </li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>
        {{ $dates->links('pagination::simple-default') }}
    </section>

    <section class="carte" aria-labelledby="titre-progression">
        <div class="carte-titre">
            <h2 id="titre-progression">{{ __('interface.progress_by_exercise') }}</h2>
            <x-icon name="trending-up" class="texte-discret" />
        </div>
        <ul class="liste">
            @foreach ($exercicesSuivis as $ex)
            <li>
                <div class="principal">
                    <a href="{{ route('progress.show', $ex->id) }}" class="titre">{{ $ex->name }}</a>
                    <div class="texte-discret petit">{{ trans_choice('interface.sessions_count', $ex->nb_seances) }}</div>
                </div>
                @if ($ex->meilleure_charge !== null)
                <span class="badge badge-contour">{{ __('interface.best') }} {{ Formatage::charge($ex->meilleure_charge) }}</span>
                @endif
                <x-icon name="chevron-right" :size="16" class="texte-discret" />
            </li>
            @endforeach
        </ul>
    </section>
</div>
@endif
@endsection
