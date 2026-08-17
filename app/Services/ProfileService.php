<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    /**
     * Update the user's profile information.
     * Resets email verification if the email address was changed.
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->fill($data);

        // Reset email verification when the email address changes
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user->fresh();
    }

    /**
     * Update the user's password with a hashed value.
     */
    public function updatePassword(User $user, string $newPassword): User
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return $user->fresh();
    }

    /**
     * Delete the user's account, log them out, and invalidate the session.
     */
    public function deleteAccount(User $user, Request $request): void
    {
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
