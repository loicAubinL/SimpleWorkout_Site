@csrf

<label for="name">Nom</label>
<input type="text" id="name" name="name"
    value="{{ old('name', $split?->name) }}"
    @error('name') aria-invalid="true" aria-describedby="name-erreur" @enderror>
@error('name')
<p id="name-erreur" class="message-erreur" role="alert">{{ $message }}</p>
@enderror

<label class="case-a-cocher">
    <input type="checkbox" name="is_active" value="1"
        @checked(old('is_active', $split?->is_active))>
    Workout actif
</label>

<div class="actions">
    <button type="submit">Enregistrer</button>
    <a href="{{ route('workout-splits.index') }}">Annuler</a>
</div>