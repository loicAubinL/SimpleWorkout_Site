@props(['exercice'])
@php use App\Support\Formatage; @endphp
<div {{ $attributes->merge(['class' => 'chiffres']) }}>
    <span class="chiffre"><b>{{ $exercice->target_sets }} × {{ $exercice->target_reps }}</b></span>
    @if ($exercice->weight !== null)
    <span class="chiffre"><x-icon name="weight" :size="13" /> <b>{{ Formatage::charge($exercice->weight) }}</b></span>
    @endif
    @if ($exercice->rest_seconds !== null)
    <span class="chiffre"><x-icon name="timer" :size="13" /> <b>{{ Formatage::repos($exercice->rest_seconds) }}</b></span>
    @endif
    @if ($exercice->rir !== null)
    <span class="chiffre">RIR <b>{{ $exercice->rir }}</b></span>
    @endif
</div>
