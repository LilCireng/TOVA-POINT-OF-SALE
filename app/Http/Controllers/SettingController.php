<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function edit()
    {
        return view('settings.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        if ($request->filled('password')) {
            $request->validate([
                'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Password saat ini tidak cocok.');
                    }
                }],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);

            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('profile_photo')->store('foto-profil', 'public');
            $user->foto = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return redirect()->route('settings.edit')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}