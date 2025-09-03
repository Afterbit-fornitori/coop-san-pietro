<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::query()->with(['company', 'roles']);

        if ($request->user()->hasRole('company_admin')) {
            $query->whereIn('company_id', [
                $request->user()->company_id,
                ...Company::where('parent_id', $request->user()->company_id)->pluck('id')
            ]);
        } elseif (!$request->user()->hasRole('super_admin')) {
            $query->where('id', $request->user()->id);
        }

        $users = $query->paginate($request->input('per_page', 15));

        return UserResource::collection($users);
    }

    public function store(UserRequest $request): UserResource
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $user->syncRoles($request->input('roles'));

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        $user->load(['company', 'roles']);

        return new UserResource($user);
    }

    public function update(UserRequest $request, User $user): UserResource
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        $user->syncRoles($request->input('roles'));

        return new UserResource($user);
    }

    public function destroy(User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }

    public function profile(Request $request): UserResource
    {
        return new UserResource($request->user()->load(['company', 'roles']));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return new UserResource($user);
    }
}
