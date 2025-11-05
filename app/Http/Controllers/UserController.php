<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::cases();
        return view('users.index', [
            'title' => 'User',
            'roles' => $roles,
        ]);
    }
}
