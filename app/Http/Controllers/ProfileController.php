<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // 1. Manejar la FOTO de Perfil
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile-photos', 'public');

            // Opcional: Eliminar la foto antigua si existe
            if ($request->user()->profile_photo_path) {
                Storage::disk('public')->delete($request->user()->profile_photo_path);
            }

            $request->user()->profile_photo_path = $photoPath;
        }

        // 2. Manejar los Nuevos Campos Profesionales
        $request->user()->job_title = $request->input('job_title');
        $request->user()->assigned_line = $request->input('assigned_line');

        // ... (resto de la lógica de email y guardado)

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function deletePhoto(Request $request): RedirectResponse
    {
        // Eliminar la foto del disco (Storage)
        if ($request->user()->profile_photo_path) {
            Storage::disk('public')->delete($request->user()->profile_photo_path);
        }

        // Eliminar la referencia de la foto en la base de datos
        $request->user()->forceFill([
            'profile_photo_path' => null,
        ])->save();

        return Redirect::route('profile.edit')->with('status', 'photo-deleted');
    }
}
