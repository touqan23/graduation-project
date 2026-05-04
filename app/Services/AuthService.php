<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Authenticate user by email + password.
     * Handles lockout check, failed attempt tracking, and token creation.
     *
     * @throws ValidationException
     */

    /*public function authenticate(string $email, string $password): array
    {
        // Use select to load only what we need — avoid pulling large columns
        $user = User::select([
            'id', 'name', 'email', 'password',
            'failed_attempts', 'locked_until', 'last_failed_at',
        ])
            ->where('email', $email)
            ->first();

        // 1. User not found
        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        // 2. Account locked?
        if ($user->isLocked()) {
            throw ValidationException::withMessages([
                'email' => [
                    "Account locked for {$user->lockRemainingMinutes()} minutes due to too many failed attempts."
                ],
            ]);
        }

        // 3. Wrong password
        if (! Hash::check($password, $user->password)) {
            $user->recordFailedAttempt(); // handles locking internally
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        // 4. Success — reset lockout, create token
        $user->clearLockout();

        // Load roles via eager loading now (needed for the resource)
        $user->load('roles:id,name');

        // Create Sanctum token scoped to this user's role
        $token = $user->createToken(
            name:       'api-token',
            abilities:  $user->getRoleNames()->toArray(),
            expiresAt:  now()->addDays(7),
        )->plainTextToken;

        // Log successful login
        activity('auth')
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'ip'         => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User logged in successfully');

        return compact('user', 'token');
    }*/

    public function authenticate(string $loginKey, string $password): array
    {
        $isEmail = filter_var($loginKey, FILTER_VALIDATE_EMAIL);
        $field = $isEmail ? 'email' : 'name';

        $user = User::select([
            'id', 'name', 'email', 'password',
        ])
            ->with('roles:id,name')
            ->where($field, $loginKey)
            ->first();

        // 1. User not found
        if (! $user) {
            throw ValidationException::withMessages([
                'identifier' => ['Invalid credentials.'],
            ]);
        }

        // 2. Admin login (username)
        if ($user->hasRole('admin') && $isEmail) {
            throw ValidationException::withMessages([
                'identifier' => ['Invalid credentials, enter your username again.'],
            ]);
        }

        // 3. Account locked
        if ($user->isLocked()) {
            throw ValidationException::withMessages([
                'identifier' => [
                    'message' => "Account locked for {$user->lockRemainingMinutes()} minutes due to too many failed attempts."
                ],
            ]);
        }

        // 4. Wrong password
        if (! Hash::check($password, $user->password)) {
            $user->recordFailedAttempt();
            throw ValidationException::withMessages([
                'identifier' => ['Invalid credentials, enter your password again.'],
            ]);
        }

        // 5. Success — reset lockout, create token
        $user->clearLockout();

        $token = $user->createToken(
            name:       'api-token',
            abilities:  $user->getRoleNames()->toArray(),
            expiresAt:  now()->addDays(7),
        )->plainTextToken;

        activity('auth')
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'ip'         => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User logged in successfully');

        return compact('user', 'token');
    }
}
