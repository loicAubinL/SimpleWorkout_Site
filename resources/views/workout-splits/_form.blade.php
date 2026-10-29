@csrf

<label for="program">Programme (fichier JSON)</label>
<input type="file" id="program" name="program" accept=".json,application/json"
    @error('program') aria-invalid="true" aria-describedby="program-erreur" @enderror>
@error('program')
<p id="program-erreur" class="message-erreur" role="alert">{{ $message }}</p>
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