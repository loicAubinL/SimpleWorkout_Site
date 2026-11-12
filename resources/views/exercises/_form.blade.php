@csrf

<x-champ name="name" :label="__('interface.exercise_name')" :value="$exercice?->name" required maxlength="100"
    :placeholder="__('interface.exercise_name_placeholder')" />

<div class="rangee">
    <x-champ name="target_sets" type="number" :label="__('interface.sets')" :value="$exercice?->target_sets ?? 3"
        required min="1" max="20" inputmode="numeric" />
    <x-champ name="target_reps" :label="__('interface.reps')" :value="$exercice?->target_reps ?? '8-12'"
        required maxlength="20" :aide="__('interface.reps_hint')" />
</div>

<div class="rangee">
    <x-champ name="weight" type="number" :label="__('interface.weight_kg')" :value="$exercice?->weight !== null ? (float) $exercice->weight : null"
        min="0" max="1000" step="0.5" inputmode="decimal" :aide="__('interface.optional')" />
    <x-champ name="rest_seconds" type="number" :label="__('interface.rest_seconds')" :value="$exercice?->rest_seconds"
        min="0" max="900" step="15" inputmode="numeric" :aide="__('interface.optional')" />
    <x-champ name="rir" type="number" :label="__('interface.rir')" :value="$exercice?->rir"
        min="0" max="10" inputmode="numeric" :aide="__('interface.rir_hint')" />
</div>

<x-champ name="notes" type="textarea" :label="__('interface.notes')" :value="$exercice?->notes" maxlength="500" rows="2"
    :aide="__('interface.optional')" />

<div class="actions">
    <button type="submit" class="bouton"><x-icon name="save" :size="18" /> {{ __('interface.save') }}</button>
    <a href="{{ route('workout-splits.show', $jour->workout_split_id) }}#jour-{{ $jour->id }}" class="bouton bouton-fantome">{{ __('interface.cancel') }}</a>
</div>
