<?php

namespace App\Services;

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\DocumentReference;

class FirestoreService
{
    private FirestoreClient $db;

    public function __construct()
    {
        $this->db = new FirestoreClient([
            'projectId' => config('firestore.project_id'),
            // The Google PHP clients read credentials from GOOGLE_APPLICATION_CREDENTIALS.
        ]);
    }

    public function db(): FirestoreClient
    {
        return $this->db;
    }

    /**
     * Create a DocumentReference from a "collection/docId" path.
     */
    public function ref(string $path): DocumentReference
    {
        $path = trim($path, '/');
        [$collection, $doc] = explode('/', $path, 2);
        return $this->db->collection($collection)->document($doc);
    }
}
