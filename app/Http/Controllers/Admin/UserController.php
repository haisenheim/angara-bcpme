<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Agence;
use App\Models\OrganisationEntite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureUserTokens();
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'role_id' => (string) $request->query('role_id', ''),
            'active' => (string) $request->query('active', ''),
        ];

        $items = $this->getUsersQuery()
            ->when($filters['q'] !== '', function (Builder $query) use ($filters) {
                $query->where(function (Builder $userQuery) use ($filters) {
                    $userQuery
                        ->where('name', 'like', '%' . $filters['q'] . '%')
                        ->orWhere('email', 'like', '%' . $filters['q'] . '%')
                        ->orWhere('phone', 'like', '%' . $filters['q'] . '%');
                });
            })
            ->when($filters['role_id'] !== '', fn (Builder $query) => $query->where('role_id', (int) $filters['role_id']))
            ->when($filters['active'] !== '', fn (Builder $query) => $query->where('active', (int) $filters['active']))
            ->paginate(15);

        $stats = [
            'total' => User::where('role_id', '>', 1)->count(),
            'active' => User::where('role_id', '>', 1)->where('active', 1)->count(),
            'locked' => User::where('role_id', '>', 1)->where('active', 0)->count(),
        ];

        return view('Admin/Users/index', array_merge(
            $this->getReferenceData(),
            compact('items', 'filters', 'stats')
        ));
    }

    public function getRoles()
    {
        $items = Role::where('metier', 1)->get();

        return view('/Admin/Users/roles')->with(compact('items'));
    }

    public function create()
    {
        return view('Admin/Users/create', array_merge(
            $this->getReferenceData(),
            [
                'item' => new User([
                    'active' => 1,
                    'agence_id' => 0,
                ]),
            ]
        ));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $this->normalizePayload($request->validated());
        $data['token'] = sha1(Str::uuid()->toString());
        $data['active'] = $request->boolean('active', true);

        $user = new User($data);
        $this->resetLegacyAssignments($user);
        $user->save();

        Session::flash('success', 'Compte utilisateur cree avec succes.');

        return redirect()->route('admin.users.index');
    }

    public function show($token)
    {
        $item = $this->findUserByToken($token);

        return view('Admin/Users/show', compact('item'));
    }

    public function edit($token)
    {
        $item = $this->findUserByToken($token);

        return view('Admin/Users/edit', array_merge(
            $this->getReferenceData(),
            compact('item')
        ));
    }

    public function update(UpdateUserRequest $request, $token)
    {
        $item = $this->findUserByToken($token);
        $data = $this->normalizePayload($request->validated(), false);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $item->fill($data);
        $this->resetLegacyAssignments($item);
        $item->active = $request->boolean('active');
        $item->save();

        Session::flash('success', 'Compte utilisateur mis a jour avec succes.');

        return redirect()->route('admin.users.edit', $item->token);
    }

    public function enable($token)
    {
        $user = $this->findUserByToken($token);
        $user->active = 1;
        $user->save();

        Session::flash('success', 'Le compte utilisateur a ete active.');

        return back();
    }

    public function disable($token)
    {
        $user = $this->findUserByToken($token);
        $user->active = 0;
        $user->save();

        Session::flash('success', 'Le compte utilisateur a ete verrouille.');

        return back();
    }

    protected function getReferenceData(): array
    {
        return [
            'roles' => Role::where('metier', 1)->orderBy('name')->get(),
            'agences' => Agence::query()->orderBy('name')->get(),
            'organisationEntites' => OrganisationEntite::query()->where('active', true)->orderBy('type')->orderBy('name')->get(),
        ];
    }

    protected function getUsersQuery(): Builder
    {
        return User::query()
            ->where('role_id', '>', 1)
            ->with(['role', 'agence.representation', 'organisationEntite'])
            ->orderByDesc('id');
    }

    protected function findUserByToken(string $token): User
    {
        return $this->getUsersQuery()
            ->where('token', $token)
            ->firstOrFail();
    }

    protected function normalizePayload(array $validated, bool $withPassword = true): array
    {
        $organisationType = $validated['organisation_type'] ?? null;
        $agenceId = (int) ($validated['agence_id'] ?? 0);
        $entiteId = $validated['organisation_entite_id'] ?? null;
        $entiteId = filled($entiteId) ? (int) $entiteId : null;

        if ($organisationType === 'agence') {
            $entiteId = null;
        } elseif ($organisationType === 'entite') {
            $agenceId = 0;
        } else {
            // fallback compat: si agence_id présent => agence, sinon entité
            $organisationType = $agenceId > 0 ? 'agence' : 'entite';
            if ($organisationType === 'agence') {
                $entiteId = null;
            } else {
                $agenceId = 0;
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role_id' => (int) $validated['role_id'],
            'agence_id' => $agenceId,
            'organisation_type' => $organisationType,
            'organisation_entite_id' => $entiteId,
        ];

        if ($withPassword || array_key_exists('password', $validated)) {
            $password = $validated['password'] ?? null;
            if (filled($password)) {
                $data['password'] = Hash::make($password);
            }
        }

        return $data;
    }

    protected function ensureUserTokens(): void
    {
        User::query()
            ->where('role_id', '>', 1)
            ->where(function (Builder $query) {
                $query->whereNull('token')->orWhere('token', '');
            })
            ->get()
            ->each(function (User $user) {
                $user->forceFill([
                    'token' => sha1(Str::uuid()->toString()),
                ])->saveQuietly();
            });
    }

    protected function resetLegacyAssignments(User $user): void
    {
        $user->representation_id = 0;
        $user->banque_id = 0;
        $user->secteur_id = 0;
    }
}
