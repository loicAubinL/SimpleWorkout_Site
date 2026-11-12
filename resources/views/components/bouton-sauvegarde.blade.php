@props(['split', 'sauvegarde' => false])
{{-- Bouton bascule « Sauvegarder / Retirer » (formulaire POST ou DELETE avec CSRF). --}}
@auth
@if ($sauvegarde)
<form method="post" action="{{ route('saved.destroy', $split) }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="bouton bouton-secondaire bouton-petit" aria-pressed="true">
        <x-icon name="bookmark-check" :size="16" /> {{ __('interface.unsave') }}<span class="sr-only"> : {{ $split->name }}</span>
    </button>
</form>
@elseif (auth()->user()->can('save', $split))
<form method="post" action="{{ route('saved.store', $split) }}">
    @csrf
    <button type="submit" class="bouton bouton-secondaire bouton-petit" aria-pressed="false">
        <x-icon name="bookmark" :size="16" /> {{ __('interface.save_workout') }}<span class="sr-only"> : {{ $split->name }}</span>
    </button>
</form>
@endif
@endauth
