@csrf

<x-champ name="name" :label="__('interface.day_name')" :value="$jour?->name" required maxlength="100"
    :placeholder="__('interface.day_name_placeholder')" />

<label class="case-a-cocher">
    <input type="checkbox" name="is_rest_day" value="1" @checked(old('is_rest_day', $jour?->is_rest_day))>
    <span><strong>{{ __('interface.rest_day') }}</strong><span>{{ __('interface.rest_day_hint') }}</span></span>
</label>

<div class="actions">
    <button type="submit" class="bouton"><x-icon name="save" :size="18" /> {{ __('interface.save') }}</button>
    <a href="{{ route('workout-splits.show', $split) }}" class="bouton bouton-fantome">{{ __('interface.cancel') }}</a>
</div>
