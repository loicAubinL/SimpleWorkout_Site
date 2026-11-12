@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'aide' => null,
    'id' => null,
])
{{-- Champ de formulaire avec étiquette, aide et message d'erreur liés pour l'accessibilité. --}}
@php
    $id = $id ?? $name;
    $decrit = trim(($aide ? $id . '-aide ' : '') . ($errors->has($name) ? $id . '-erreur' : ''));
@endphp
<div class="champ">
    <label for="{{ $id }}">{{ $label }}</label>
    @if ($type === 'textarea')
    <textarea id="{{ $id }}" name="{{ $name }}"
        @if ($decrit) aria-describedby="{{ $decrit }}" @endif
        @error($name) aria-invalid="true" @enderror
        {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @elseif ($type === 'select')
    <select id="{{ $id }}" name="{{ $name }}"
        @if ($decrit) aria-describedby="{{ $decrit }}" @endif
        @error($name) aria-invalid="true" @enderror
        {{ $attributes }}>{{ $slot }}</select>
    @else
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
        @unless ($type === 'password') value="{{ old($name, $value) }}" @endunless
        @if ($decrit) aria-describedby="{{ $decrit }}" @endif
        @error($name) aria-invalid="true" @enderror
        {{ $attributes }}>
    @endif
    @if ($aide)
    <p id="{{ $id }}-aide" class="aide">{{ $aide }}</p>
    @endif
    @error($name)
    <p id="{{ $id }}-erreur" class="message-erreur" role="alert">{{ $message }}</p>
    @enderror
</div>
