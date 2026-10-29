@csrf

<label for="name">Nom</label>
<input type="text" id="name" name="name"
    value="{{ old('name', $split?->name) }}"
    @error('name') aria-invalid="true" aria-describedby="name-erreur" @enderror>
@error('name')
<p id="name-erreur" class="message-erreur" role="alert">{{ $message }}</p>
@enderror

@if ($split?->program_path)
<div class="fichier-actuel">
    <span class="aide">
        Fichier actuel :
        <a href="{{ Storage::disk('public')->url($split->program_path) }}" download>télécharger</a>.
        Choisir un nouveau fichier le remplacera.
    </span>
    <button type="submit" form="supprimer-programme" class="bouton-danger bouton-poubelle"
        aria-label="Retirer le fichier du programme" title="Retirer le fichier">🗑</button>
</div>
@endif

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