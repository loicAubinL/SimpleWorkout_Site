@if ($splits->isEmpty())
<p class="etat-vide">{{ __('interface.no_workouts') }}</p>
@else
<ul>
    @foreach ($splits as $split)
    <li>
        <a href="{{ route('workout-splits.show', $split) }}">
            <span>
                {{ $split->name }}
                <span class="exercice-details">{{ __('interface.by', ['name' => $split->user->name]) }}</span>
            </span>
            <span class="badge">{{ __('interface.days_count', ['count' => $split->workout_days_count]) }}</span>
        </a>
    </li>
    @endforeach
</ul>
@endif