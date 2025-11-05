<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $query = User::query();
        return DataTables::eloquent($query)->toJson();
    }

    public function show(User $user)
    {
        return $this->sendResponse($user);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'email'     => 'required|max:100|unique:users,email',
            'role'      => ['required', Rule::enum(Role::class)],
            'password'  => 'required|string|min:5|max:100'
        ]);
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
        ]);
        return $this->sendResponse($user, 'Created!');
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'email'     => 'required|max:100|unique:users,email,' . $user->id,
            'role'      => ['required', Rule::enum(Role::class)],
            'password'  => 'nullable|string|min:5|max:100'
        ]);
        $param = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
        ];
        if ($request->filled('password')) {
            $param['password'] = Hash::make($request->password);
        }
        $user->update($param);
        return $this->sendResponse($user, 'Updated!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $this->sendResponse($user, 'Deleted!');
    }

    public function destroy_batch(Request $request)
    {
        $this->validate($request, [
            'ids'       => 'required|array',
            'ids.*'     => 'integer|exists:users,id',
        ]);
        $deleted = User::whereIn('id', $request->ids)->delete();

        return $this->sendResponse([
            'deleted_count' => $deleted
        ], 'Data deleted successfully.');
    }
}
