<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Requests\User\AssignRoleRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::where('active', true)->paginate(10);
        return new UserCollection($users);
    }

    public function store(Request $request)
    {
        //
        $this->authorize('create', User::class);
        return response([
            'status' => 'ok',
            'message' => 'Los usuarios solo pueden registrarse por invitación.',
        ], 405);
    }

    public function show(string $id)
    {
        //
        $user = User::find($id);

        if (!$user) {
            return response([
                'errors' => [
                    'El usuario no existe.'
                ]
            ], 404);
        }

        $this->authorize('view', $user);
        $user->load('roles');

        return response([
            'user' => new UserResource($user),
        ], 200);
    }

    public function update(UserRequest $request, string $id)
    {
        //
        $user = User::find($id);
        $data = $request->validated();
        $auth = Auth::user();

        if (!$user) {
            return response([
                'errors' => [
                    'El usuario no existe.'
                ]
            ], 401);
        }

        $this->authorize('update', $user);

        if (!Hash::check($data['confirmation_password'], $auth->password)) {
            return response([
                'errors' => [
                    'Tu contraseña no es correcta.'
                ]
            ], 401);
        }

        $user->update($data);

        return response([
            'status' => 'ok',
            'message' => 'Datos actualizados correctamente.',
            'user' => $user,
        ], 201);
    }

    public function destroy(Request $request, string $id)
    {
        //
        $auth = Auth::user();
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $request->validate([
            'confirmation_password' => 'required|string',
        ]);

        if (!Hash::check($request->confirmation_password, $auth->password)) {
            return response([
                'errors' => [
                    'Tu contraseña de confirmación es incorrecta.'
                ]
            ], 401);
        }

        $user->delete();

        return response([
            'status' => 'ok',
            'message' => 'Usuario eliminado correctamente.',
        ], 201);
    }

    public function assignRole(AssignRoleRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if (!Hash::check($data['password'], $user->password)) {
            return response([
                'errors' => [
                    'Tu contraseña no es correcta.'
                ]
            ], 401);
        }

        $user = User::findOrFail($data['user_id']);
        $user->syncRoles($data['role']);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties(['id' => $user->id, 'role' => $data['role']])
            ->log('Se asignó el rol');

        return response([
            'status' => 'ok',
            'message' => 'Rol asignado correctamente.',
            'user' => $user->load('roles'),
        ], 201);
    }
}
