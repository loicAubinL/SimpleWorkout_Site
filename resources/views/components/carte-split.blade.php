@props(['split', 'avecAuteur' => false])
<article class="carte carte-split">
    <div class="meta">
        @if ($split->is_active)
        <span class="badge badge-accent"><x-icon name="flame" :size="13" /> {{ __('interface.active') }}</span>
        @endif
        @if ($split->is_public)
        <span class="badge"><x-icon name="globe" :size="13" /> {{ __('interface.public') }}</span>
        @else
        <span class="badge"><x-icon name="lock" :size="13" /> {{ __('interface.private') }}</span>
        @endif
    </div>

    <h3><a href="{{ route('workout-splits.show', $split) }}">{{ $split->name }}</a></h3>

    @if ($split->description)
    <p class="description">{{ $split->description }}</p>
    @endif

    <div class="meta">
        <span><x-icon name="calendar" :size="15" /> {{ trans_choice('interface.days_count', $split->workout_days_count ?? 0) }}</span>
        @isset($split->exercises_count)
        <span><x-icon name="dumbbell" :size="15" /> {{ trans_choice('interface.exercises_count', $split->exercises_count) }}</span>
        @endisset
        @if ($avecAuteur)
        <span><x-icon name="user" :size="15" /> {{ $split->user->name }}</span>
        @endif
    </div>

    @if (isset($actions) && $actions->isNotEmpty())
    <div class="actions">{{ $actions }}</div>
    @endif
</article>
