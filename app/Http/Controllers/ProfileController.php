<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profiles', [
            'title' => 'Profiles',
            'user'  => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:100',
        ]);
        $user = auth()->user();
        $user->update([
            'name'  => $request->name,
        ]);
        return redirect()->route('profiles.index')->with('success', 'Profile Updated!');
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', 'min:5'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profiles.index')->with('success', 'Password Updated!');
    }
}
