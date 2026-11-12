@props(['icone' => 'dumbbell'])
<div {{ $attributes->merge(['class' => 'etat-vide']) }}>
    <x-icon :name="$icone" :size="32" />
    {{ $slot }}
</div>
