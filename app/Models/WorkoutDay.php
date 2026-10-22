<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutDay extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'day_of_week', 'position', 'is_rest_day'];

    public function workoutSplit(): BelongsTo
    {
        return $this->belongsTo(WorkoutSplit::class);
    }
}
