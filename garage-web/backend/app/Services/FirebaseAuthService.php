<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class FirebaseAuthService
{
    private Auth $auth;

    public function __construct()
    {
        $credentials = config('firebase.credentials');
        $factory = (new Factory())
            ->withProjectId(config('firebase.project_id'));

        if ($credentials) {
            $factory = $factory->withServiceAccount($credentials);
        }

        $this->auth = $factory->createAuth();
    }

    public function auth(): Auth
    {
        return $this->auth;
    }
}
