<?php

namespace App\Repositories;

use App\Services\FirestoreService;
use Google\Cloud\Firestore\DocumentSnapshot;
use Illuminate\Support\Arr;

class FirestoreRepository
{
    public function __construct(private readonly FirestoreService $firestore) {}

    public function list(string $collection): array
    {
        $docs = $this->firestore->db()->collection($collection)->documents();
        $out = [];
        foreach ($docs as $doc) {
            /** @var DocumentSnapshot $doc */
            if (!$doc->exists()) {
                continue;
            }
            $out[] = ['id' => $doc->id()] + $this->normalize($doc->data());
        }
        return $out;
    }

    public function get(string $collection, string $id): ?array
    {
        $snap = $this->firestore->db()->collection($collection)->document($id)->snapshot();
        if (!$snap->exists()) {
            return null;
        }
        return ['id' => $snap->id()] + $this->normalize($snap->data());
    }

    public function create(string $collection, string $id, array $data): array
    {
        $ref = $this->firestore->db()->collection($collection)->document($id);
        $ref->set($data);
        return ['id' => $id] + $this->normalize($data);
    }

    public function createAutoId(string $collection, array $data): array
    {
        $ref = $this->firestore->db()->collection($collection)->newDocument();
        $ref->set($data);
        return ['id' => $ref->id()] + $this->normalize($data);
    }

    public function update(string $collection, string $id, array $data): ?array
    {
        $ref = $this->firestore->db()->collection($collection)->document($id);
        $snap = $ref->snapshot();
        if (!$snap->exists()) {
            return null;
        }
        // Merge by default.
        $ref->set($data, ['merge' => true]);
        $merged = array_merge($snap->data(), $data);
        return ['id' => $id] + $this->normalize($merged);
    }

    public function delete(string $collection, string $id): bool
    {
        $ref = $this->firestore->db()->collection($collection)->document($id);
        $snap = $ref->snapshot();
        if (!$snap->exists()) {
            return false;
        }
        $ref->delete();
        return true;
    }

    /**
     * Convert Firestore types (DocumentReference, Timestamp...) to JSON-friendly values.
     */
    private function normalize(array $data): array
    {
        $normalized = [];
        foreach ($data as $k => $v) {
            if ($v instanceof \Google\Cloud\Firestore\DocumentReference) {
                $normalized[$k] = $v->name();
            } elseif ($v instanceof \Google\Cloud\Core\Timestamp) {
                $normalized[$k] = $v->get()->format(DATE_ATOM);
            } else {
                $normalized[$k] = $v;
            }
        }
        return $normalized;
    }
}
