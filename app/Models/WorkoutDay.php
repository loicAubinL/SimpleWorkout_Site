<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutDay extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'day_of_week', 'position', 'is_rest_day'];

    protected function casts(): array
    {
        return ['is_rest_day' => 'boolean'];
    }

    public function workoutSplit(): BelongsTo
    {
        return $this->belongsTo(WorkoutSplit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class)->orderBy('position')->orderBy('id');
    }
}
