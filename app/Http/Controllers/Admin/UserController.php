<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnregistrerWorkoutSplitRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * Gestion des comptes par le responsable (route protégée par EnsureManager).
 */
class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filtres = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in([User::ROLE_MEMBER, User::ROLE_MANAGER])],
        ]);
        $terme = trim($filtres['q'] ?? '');

        $users = User::query()
            ->withCount(['workoutSplits', 'loggedSets'])
            ->when($terme !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', '%' . $terme . '%')
                ->orWhere('email', 'like', '%' . $terme . '%')))
            ->when($filtres['role'] ?? null, fn ($q, $role) => $q->where('role', $role))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'terme' => $terme,
            'role' => $filtres['role'] ?? null,
            'total' => User::count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', ['compte' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $donnees = $request->validate($this->regles(null));

        $compte = new User(collect($donnees)->only(['name', 'email', 'password'])->all());
        $compte->role = $donnees['role'];
        $compte->save();

        return redirect()->route('admin.users.show', $compte)->with('status', __('interface.user_created'));
    }

    public function show(User $user): View
    {
        $user->loadCount(['workoutSplits', 'savedWorkouts', 'loggedSets']);

        $splits = $user->workoutSplits()
            ->withCount(['workoutDays', 'exercises'])
            ->orderByDesc('is_active')
            ->latest()
            ->get();

        return view('admin.users.show', ['compte' => $user, 'splits' => $splits]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', ['compte' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $donnees = $request->validate($this->regles($user));

        if ($user->isManager() && $donnees['role'] !== User::ROLE_MANAGER && $this->dernierResponsable()) {
            throw ValidationException::withMessages(['role' => __('interface.last_manager')]);
        }

        $user->fill(collect($donnees)->only(['name', 'email'])->all());
        if (! empty($donnees['password'])) {
            $user->password = $donnees['password'];
        }
        $user->role = $donnees['role'];
        $user->save();

        return redirect()->route('admin.users.show', $user)->with('status', __('interface.user_updated'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['compte' => __('interface.cannot_delete_self')]);
        }

        if ($user->isManager() && $this->dernierResponsable()) {
            return back()->withErrors(['compte' => __('interface.last_manager')]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', __('interface.user_deleted'));
    }

    /**
     * Créer un workout pour ce compte ; le responsable complète ensuite les jours et exercices.
     */
    public function createSplit(User $user): View
    {
        return view('admin.users.create-split', ['compte' => $user]);
    }

    public function storeSplit(EnregistrerWorkoutSplitRequest $request, User $user): RedirectResponse
    {
        $donnees = $request->validated();

        $split = DB::transaction(function () use ($user, $donnees) {
            if ($donnees['is_active']) {
                $user->workoutSplits()->update(['is_active' => false]);
            }

            return $user->workoutSplits()->create($donnees);
        });

        return redirect()->route('workout-splits.show', $split)->with('status', __('interface.created'));
    }

    private function regles(?User $user): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user?->id)],
            'role' => ['required', Rule::in([User::ROLE_MEMBER, User::ROLE_MANAGER])],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(8)],
        ];
    }

    private function dernierResponsable(): bool
    {
        return User::where('role', User::ROLE_MANAGER)->count() <= 1;
    }
}
