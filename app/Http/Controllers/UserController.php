<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\InviteCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

        $this->middleware(['can:users.create'], ['only' => ['store']]);
        $this->middleware(['can:users.read'], ['only' => ['index', 'show']]);
        $this->middleware(['can:users.update'], ['only' => ['update']]);
        $this->middleware(['can:users.delete'], ['only' => ['destroy']]);
    }
    public function index(IndexUserRequest $request)
    {
        $validated = $request->validated();

        $limit = $validated['limit'] ?? 10;
        $search = $validated['search'] ?? null;
        $orderBy = $validated['orderBy'] ?? 'created_at';
        $order = $validated['order'] ?? 'desc';

        $role_id = $validated['role_id'] ?? null;

        $query = User::query();
        if ($search) {
            $query->where('surname', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('patronymic', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');
        }

        if ($role_id) {
            $query->whereHas('roles', function ($query) use ($role_id) {
                $query->where('id', $role_id);
            });
        }

        $users = $query->orderBy($orderBy, $order)->paginate($limit);

        return new UserCollection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        $user->assignRole($request->input('roles'));

        $invite_code = InviteCodeController::generateCode();
        $user->invite_code()->create(['code' => $invite_code]);

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        $user->syncRoles($request->input('roles'));

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $invite_code = InviteCode::where('user_id', $user->id)->first();
        if ($invite_code) {
            $invite_code->delete();
        }
        $user->delete();
    }

    public function changeEmail(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'email' => $validated['email'],
        ]);

        $user->save();

        return new UserResource($user);
    }
}
