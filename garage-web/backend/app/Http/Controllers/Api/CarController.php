<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FirestoreService;
use Google\Cloud\Core\Timestamp;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct(private readonly FirestoreService $firestore) {}

    public function index(Request $request)
    {
        $uid = $request->attributes->get('firebase_uid');
        $userRef = $this->firestore->ref("users/{$uid}");

        $query = $this->firestore->db()->collection('cars')->where('user', '==', $userRef);
        $docs = $query->documents();

        $out = [];
        foreach ($docs as $doc) {
            if (!$doc->exists()) continue;
            $data = $doc->data();
            $out[] = [
                'id' => $doc->id(),
                'user' => $data['user']?->name(),
                'model' => $data['model'] ?? null,
                'plate_number' => $data['plate_number'] ?? null,
                'created_at' => ($data['created_at'] ?? null) instanceof Timestamp ? $data['created_at']->get()->format(DATE_ATOM) : ($data['created_at'] ?? null),
            ];
        }

        return response()->json($out);
    }

    public function store(Request $request)
    {
        $uid = $request->attributes->get('firebase_uid');
        $data = $request->validate([
            'model' => ['required', 'string'],
            'plate_number' => ['required', 'string'],
        ]);

        $ref = $this->firestore->db()->collection('cars')->newDocument();
        $ref->set([
            'user' => $this->firestore->ref("users/{$uid}"),
            'model' => $data['model'],
            'plate_number' => $data['plate_number'],
            'created_at' => new Timestamp(new \DateTimeImmutable()),
        ]);

        return response()->json(['id' => $ref->id()], 201);
    }
}
