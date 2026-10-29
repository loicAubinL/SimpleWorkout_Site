@csrf

<label for="name">{{ __('interface.name') }}</label>
<input type="text" id="name" name="name"
    value="{{ old('name', $split?->name) }}"
    @error('name') aria-invalid="true" aria-describedby="name-erreur" @enderror>
@error('name')
<p id="name-erreur" class="message-erreur" role="alert">{{ $message }}</p>
@enderror

@if ($split?->program_path)
<div class="fichier-actuel">
    <span class="aide">
        {{ __('interface.current_file') }}
        <a href="{{ Storage::disk('public')->url($split->program_path) }}" download>{{ __('interface.download') }}</a>.
        {{ __('interface.replace_hint') }}
    </span>
    <button type="submit" form="supprimer-programme" class="bouton-danger bouton-poubelle"
        aria-label="{{ __('interface.remove_file') }}" title="{{ __('interface.remove_file') }}">🗑</button>
</div>
@endif

<label for="program">{{ __('interface.program_file') }}</label>
<input type="file" id="program" name="program" accept=".json,application/json"
    @error('program') aria-invalid="true" aria-describedby="program-erreur" @enderror>
@error('program')
<p id="program-erreur" class="message-erreur" role="alert">{{ $message }}</p>
@enderror

<label class="case-a-cocher">
    <input type="checkbox" name="is_active" value="1"
        @checked(old('is_active', $split?->is_active))>
    {{ __('interface.is_active') }}
</label>

<div class="actions">
    <button type="submit">{{ __('interface.save') }}</button>
    <a href="{{ route('workout-splits.index') }}">{{ __('interface.cancel') }}</a>
</div>