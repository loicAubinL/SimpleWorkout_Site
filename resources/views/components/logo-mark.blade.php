@props(['size' => 32])
{{-- Logo SimpleWorkout : haltère stylisé sur un carré lime (SVG fait maison). --}}
<svg {{ $attributes }} xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" aria-hidden="true" focusable="false">
    <rect width="32" height="32" rx="8" fill="#C6F432" />
    <g fill="#0E1013">
        <rect x="3.5" y="12" width="3" height="8" rx="1.2" />
        <rect x="7.5" y="8.5" width="4" height="15" rx="1.4" />
        <rect x="11.5" y="14.5" width="9" height="3" rx="1" />
        <rect x="20.5" y="8.5" width="4" height="15" rx="1.4" />
        <rect x="25.5" y="12" width="3" height="8" rx="1.2" />
    </g>
</svg>
