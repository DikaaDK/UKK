<?php

namespace App\Auth;

use App\Models\Admin;
use App\Models\Siswa;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class MixedUserProvider implements UserProvider
{
    public function retrieveById($identifier): ?Authenticatable
    {
        return Siswa::query()->whereKey($identifier)->first()
            ?? Admin::query()->whereKey($identifier)->first();
    }

    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        $user = $this->retrieveById($identifier);

        if (! $user) {
            return null;
        }

        return hash_equals((string) $user->getRememberToken(), (string) $token)
            ? $user
            : null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        $user->setRememberToken($token);

        if ($user instanceof Model) {
            $user->save();
        }
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $identifier = $credentials['nis'] ?? $credentials['username'] ?? null;

        if (! is_string($identifier) || $identifier === '') {
            return null;
        }

        return Siswa::query()->where('nis', $identifier)->first()
            ?? Admin::query()->where('username', $identifier)->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plainPassword = (string) ($credentials['password'] ?? '');

        return Hash::check($plainPassword, $user->getAuthPassword());
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        // Passwords are already hashed in the create action, so no rehash is needed here.
    }
}
