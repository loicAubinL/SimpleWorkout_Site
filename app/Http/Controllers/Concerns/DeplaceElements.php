<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait DeplaceElements
{
    /**
     * Monte ou descend $element parmi $elements (déjà triés), puis renumérote les positions.
     *
     * @param  Collection<int, Model>  $elements
     */
    protected function deplacer(Collection $elements, Model $element, string $direction): void
    {
        $ordre = $elements->values();
        $index = $ordre->search(fn (Model $e) => $e->is($element));
        $cible = $direction === 'up' ? $index - 1 : $index + 1;

        if ($index === false || $cible < 0 || $cible >= $ordre->count()) {
            return;
        }

        $tableau = $ordre->all();
        [$tableau[$index], $tableau[$cible]] = [$tableau[$cible], $tableau[$index]];

        DB::transaction(function () use ($tableau) {
            foreach ($tableau as $position => $e) {
                if ($e->position !== $position) {
                    $e->update(['position' => $position]);
                }
            }
        });
    }
}
