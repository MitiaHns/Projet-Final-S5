<?php

namespace App\Http\Middleware;

use App\Services\FirebaseAuthService;
use App\Services\FirestoreService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FirebaseAuth
{
    public function __construct(
        private readonly FirebaseAuthService $firebaseAuth,
        private readonly FirestoreService $firestore,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization', '');
        if (!str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'Missing Bearer token'], 401);
        }

        $idToken = trim(substr($header, 7));

        try {
            $verified = $this->firebaseAuth->auth()->verifyIdToken($idToken);
            $uid = $verified->claims()->get('sub');
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid Firebase token', 'error' => $e->getMessage()], 401);
        }

        // Attach UID and Firestore user profile (if present).
        $request->attributes->set('firebase_uid', $uid);
        try {
            $snap = $this->firestore->db()->collection('users')->document($uid)->snapshot();
            $request->attributes->set('firestore_user', $snap->exists() ? $snap->data() : null);
        } catch (\Throwable $e) {
            $request->attributes->set('firestore_user', null);
        }

        return $next($request);
    }
}
