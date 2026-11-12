<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

class WorkoutSplit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'program_path',
        'is_active',
        'is_public',
        'current_position',
        'override_date',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_public' => 'boolean',
            'override_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workoutDays(): HasMany
    {
        return $this->hasMany(WorkoutDay::class)->orderBy('position')->orderBy('id');
    }

    public function exercises(): HasManyThrough
    {
        return $this->hasManyThrough(Exercise::class, WorkoutDay::class);
    }

    public function savedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_workouts')->withTimestamps();
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * Jour courant du cycle (selon current_position), ou null si le split n'a aucun jour.
     */
    public function currentDay(): ?WorkoutDay
    {
        $jours = $this->relationLoaded('workoutDays') ? $this->workoutDays : $this->workoutDays()->get();

        if ($jours->isEmpty()) {
            return null;
        }

        return $jours[$this->current_position % $jours->count()];
    }

    /**
     * Passe au jour suivant du cycle.
     */
    public function advance(): void
    {
        $total = $this->workoutDays()->count();

        $this->update([
            'current_position' => $total > 0 ? ($this->current_position + 1) % $total : 0,
        ]);
    }

    /**
     * Copie profonde (jours + exercices) appartenant à $user, privée et inactive.
     */
    public function duplicateFor(User $user): self
    {
        $this->loadMissing('workoutDays.exercises');

        return DB::transaction(function () use ($user) {
            $copie = $user->workoutSplits()->create([
                'name' => mb_substr(__('interface.copy_of', ['name' => $this->name]), 0, 255),
                'description' => $this->description,
                'is_active' => false,
                'is_public' => false,
                'current_position' => 0,
            ]);

            foreach ($this->workoutDays as $jour) {
                $nouveauJour = $copie->workoutDays()->create([
                    ...$jour->only(['name', 'day_of_week', 'position', 'is_rest_day']),
                    'user_id' => $user->id,
                ]);

                foreach ($jour->exercises as $exercice) {
                    $nouveauJour->exercises()->create($exercice->only([
                        'name', 'notes', 'target_sets', 'target_reps', 'weight', 'rest_seconds', 'rir', 'position',
                    ]));
                }
            }

            return $copie;
        });
    }
}
