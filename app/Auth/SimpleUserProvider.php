<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\Models\User;

class SimpleUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return User::find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        return $this->retrieveById($identifier);
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Do nothing - no database
    }

    public function retrieveByCredentials(array $credentials)
    {
        // Simple demo authentication - accept any email/password
        if (isset($credentials['email']) && isset($credentials['password'])) {
            $user = User::where('email', $credentials['email'])->first();
            if (!$user) {
                // Create a new user if doesn't exist
                $user = User::create([
                    'name' => explode('@', $credentials['email'])[0],
                    'email' => $credentials['email'],
                    'password' => bcrypt($credentials['password']),
                ]);
            }
            
            return $user;
        }
        
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Always return true for demo purposes
        return true;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // Do nothing - no database to update
    }
}
