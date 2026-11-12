<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    protected $table = 'user_progress';

    protected $fillable = ['current_position'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
