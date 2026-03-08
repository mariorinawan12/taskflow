<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit');
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Old password is not correct'
                ]);
            }
            $user->password = $request->password;
        }

        $user->save();

        return back()->with('success', 'Profile has been updated');
    }
}
