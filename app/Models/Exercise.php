<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'notes',
        'target_sets',
        'target_reps',
        'weight',
        'rest_seconds',
        'rir',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'target_sets' => 'integer',
            'rest_seconds' => 'integer',
            'rir' => 'integer',
        ];
    }

    public function workoutDay(): BelongsTo
    {
        return $this->belongsTo(WorkoutDay::class);
    }

    public function loggedSets(): HasMany
    {
        return $this->hasMany(LoggedSet::class);
    }
}
