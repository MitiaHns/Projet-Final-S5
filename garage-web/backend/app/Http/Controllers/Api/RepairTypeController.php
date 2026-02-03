<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FirestoreRepository;
use Illuminate\Http\Request;

class RepairTypeController extends Controller
{
    public function __construct(private readonly FirestoreRepository $repo) {}

    public function index()
    {
        return response()->json($this->repo->list('repairTypes'));
    }

    public function show(string $id)
    {
        $doc = $this->repo->get('repairTypes', $id);
        if (!$doc) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($doc);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'duree' => ['required', 'integer', 'min:0'],
            'prix' => ['required', 'numeric', 'min:0'],
        ]);

        $id = $data['id'];
        unset($data['id']);

        return response()->json($this->repo->create('repairTypes', $id, $data), 201);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string'],
            'duree' => ['sometimes', 'integer', 'min:0'],
            'prix' => ['sometimes', 'numeric', 'min:0'],
        ]);

        $doc = $this->repo->update('repairTypes', $id, $data);
        if (!$doc) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($doc);
    }

    public function destroy(string $id)
    {
        return response()->json([
            'deleted' => $this->repo->delete('repairTypes', $id),
        ]);
    }
}
