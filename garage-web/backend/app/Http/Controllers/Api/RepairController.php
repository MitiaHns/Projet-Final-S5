<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FirestoreService;
use Google\Cloud\Core\Timestamp;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function __construct(private readonly FirestoreService $firestore) {}

    /**
     * List repairs for the authenticated user (through their cars).
     */
    public function index(Request $request)
    {
        $uid = $request->attributes->get('firebase_uid');
        $userRef = $this->firestore->ref("users/{$uid}");

        // Get user's cars first
        $cars = $this->firestore->db()->collection('cars')->where('user', '==', $userRef)->documents();
        $carRefs = [];
        foreach ($cars as $car) {
            if ($car->exists()) {
                $carRefs[] = $this->firestore->ref('cars/'.$car->id());
            }
        }

        $out = [];
        foreach ($carRefs as $carRef) {
            $repairs = $this->firestore->db()->collection('repairs')->where('car', '==', $carRef)->documents();
            foreach ($repairs as $rep) {
                if (!$rep->exists()) continue;
                $d = $rep->data();
                $out[] = [
                    'id' => $rep->id(),
                    'car' => $d['car']?->name(),
                    'repair_type' => $d['repair_type']?->name(),
                    'start_time' => ($d['start_time'] ?? null) instanceof Timestamp ? $d['start_time']->get()->format(DATE_ATOM) : ($d['start_time'] ?? null),
                    'end_time' => ($d['end_time'] ?? null) instanceof Timestamp ? $d['end_time']->get()->format(DATE_ATOM) : ($d['end_time'] ?? null),
                    'price' => $d['price'] ?? null,
                    'duration_seconds' => $d['duration_seconds'] ?? null,
                ];
            }
        }

        return response()->json($out);
    }

    /**
     * Create a repair request for a given car and repair type.
     */
    public function store(Request $request)
    {
        $uid = $request->attributes->get('firebase_uid');

        $data = $request->validate([
            'car_id' => ['required', 'string'],
            'repair_type_id' => ['required', 'string'],
        ]);

        // Verify car belongs to user
        $carRef = $this->firestore->ref('cars/'.$data['car_id']);
        $carSnap = $carRef->snapshot();
        if (!$carSnap->exists()) {
            return response()->json(['message' => 'Car not found'], 404);
        }
        $expectedUser = $this->firestore->ref("users/{$uid}");
        if (($carSnap->data()['user'] ?? null)?->name() !== $expectedUser->name()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $repairTypeRef = $this->firestore->ref('repairTypes/'.$data['repair_type_id']);
        if (!$repairTypeRef->snapshot()->exists()) {
            return response()->json(['message' => 'Repair type not found'], 404);
        }

        $ref = $this->firestore->db()->collection('repairs')->newDocument();
        $ref->set([
            'car' => $carRef,
            'repair_type' => $repairTypeRef,
            'start_time' => new Timestamp(new \DateTimeImmutable()),
            'end_time' => null,
            'price' => null,
            'duration_seconds' => null,
        ]);

        // initial status
        $statusRef = $this->firestore->ref('statuses/en_attente');
        $this->firestore->db()->collection('repairStatuses')->newDocument()->set([
            'repair' => $this->firestore->ref('repairs/'.$ref->id()),
            'status' => $statusRef,
            'timestamp' => new Timestamp(new \DateTimeImmutable()),
            'changed_by' => $expectedUser,
        ]);

        return response()->json(['id' => $ref->id()], 201);
    }
}
