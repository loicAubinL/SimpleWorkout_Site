<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('account.show', [
            'user' => $user,
            'actif' => $user->workoutSplits()->where('is_active', true)->first(),
            'splits' => $user->workoutSplits()->withCount('workoutDays')->latest()->get(),
        ]);
    }
}
