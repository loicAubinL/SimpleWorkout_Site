<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSplit;
use Illuminate\Contracts\View\View;

class WorkoutPublicController extends Controller
{
    public function __invoke(): View
    {
        $splits = WorkoutSplit::with('user:id,name')
            ->withCount('workoutDays')
            ->latest()
            ->paginate(10);

        return view('workout-splits.publics', compact('splits'));
    }
}
