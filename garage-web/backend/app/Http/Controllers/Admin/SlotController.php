<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\FirestoreRepository;
use Google\Cloud\Core\Timestamp;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function __construct(private readonly FirestoreRepository $repo) {}

    public function index()
    {
        return response()->json($this->repo->list('slots'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slot_number' => ['required', 'integer', 'min:1'],
        ]);

        $doc = $this->repo->createAutoId('slots', [
            'slot_number' => $data['slot_number'],
            'is_occupied' => false,
            'created_at' => new Timestamp(new \DateTimeImmutable()),
        ]);

        return response()->json($doc, 201);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'is_occupied' => ['sometimes', 'boolean'],
            'slot_number' => ['sometimes', 'integer', 'min:1'],
        ]);

        $doc = $this->repo->update('slots', $id, $data);
        if (!$doc) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($doc);
    }
}
