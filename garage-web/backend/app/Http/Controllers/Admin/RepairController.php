<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirestoreService;
use Google\Cloud\Core\Timestamp;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function __construct(private readonly FirestoreService $firestore) {}

    public function index()
    {
        $docs = $this->firestore->db()->collection('repairs')->documents();
        $out = [];
        foreach ($docs as $doc) {
            if (!$doc->exists()) continue;
            $d = $doc->data();
            $out[] = [
                'id' => $doc->id(),
                'car' => $d['car']?->name(),
                'repair_type' => $d['repair_type']?->name(),
                'start_time' => ($d['start_time'] ?? null) instanceof Timestamp ? $d['start_time']->get()->format(DATE_ATOM) : ($d['start_time'] ?? null),
                'end_time' => ($d['end_time'] ?? null) instanceof Timestamp ? $d['end_time']->get()->format(DATE_ATOM) : ($d['end_time'] ?? null),
                'price' => $d['price'] ?? null,
                'duration_seconds' => $d['duration_seconds'] ?? null,
            ];
        }
        return response()->json($out);
    }

    /**
     * Update the status of a repair (writes in repairStatuses) and optionally
     * closes the repair (end_time, price, duration_seconds).
     */
    public function updateStatus(Request $request, string $repairId)
    {
        $data = $request->validate([
            'status_id' => ['required', 'string'], // en_cours, terminée, en_attente
            'close' => ['sometimes', 'boolean'],
        ]);

        $repairRef = $this->firestore->ref('repairs/'.$repairId);
        $repairSnap = $repairRef->snapshot();
        if (!$repairSnap->exists()) {
            return response()->json(['message' => 'Repair not found'], 404);
        }

        $statusRef = $this->firestore->ref('statuses/'.$data['status_id']);
        if (!$statusRef->snapshot()->exists()) {
            return response()->json(['message' => 'Status not found'], 404);
        }

        // track status changes
        $this->firestore->db()->collection('repairStatuses')->newDocument()->set([
            'repair' => $repairRef,
            'status' => $statusRef,
            'timestamp' => new Timestamp(new \DateTimeImmutable()),
            // admin change: we keep null or a dedicated admin reference
            'changed_by' => null,
        ]);

        // Optionally close repair and compute price/duration
        if (($data['close'] ?? false) || $data['status_id'] === 'terminée') {
            $repairData = $repairSnap->data();
            $start = ($repairData['start_time'] ?? null) instanceof Timestamp ? $repairData['start_time']->get() : null;
            $end = new \DateTimeImmutable();
            $duration = $start ? max(0, $end->getTimestamp() - $start->getTimestamp()) : null;

            // price from repairTypes
            $price = null;
            if (isset($repairData['repair_type']) && $repairData['repair_type'] instanceof \Google\Cloud\Firestore\DocumentReference) {
                $rt = $repairData['repair_type']->snapshot();
                if ($rt->exists()) {
                    $price = $rt->data()['prix'] ?? null;
                }
            }

            $repairRef->set([
                'end_time' => new Timestamp($end),
                'duration_seconds' => $duration,
                'price' => $price,
            ], ['merge' => true]);
        }

        return response()->json(['message' => 'Status updated']);
    }
}
