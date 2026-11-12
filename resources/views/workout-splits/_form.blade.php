@csrf

<x-champ name="name" :label="__('interface.name')" :value="$split?->name" required maxlength="255"
    :placeholder="__('interface.split_name_placeholder')" />

<x-champ name="description" type="textarea" :label="__('interface.description')" :value="$split?->description"
    maxlength="1000" rows="3" :aide="__('interface.description_hint')" />

<label class="case-a-cocher">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $split?->is_active))>
    <span><strong>{{ __('interface.is_active') }}</strong><span>{{ __('interface.is_active_hint') }}</span></span>
</label>

<label class="case-a-cocher">
    <input type="checkbox" name="is_public" value="1" @checked(old('is_public', $split?->is_public))>
    <span><strong>{{ __('interface.is_public') }}</strong><span>{{ __('interface.is_public_hint') }}</span></span>
</label>

<div class="actions">
    <button type="submit" class="bouton"><x-icon name="save" :size="18" /> {{ __('interface.save') }}</button>
    <a href="{{ $split ? route('workout-splits.show', $split) : route('workout-splits.index') }}" class="bouton bouton-fantome">{{ __('interface.cancel') }}</a>
</div>
