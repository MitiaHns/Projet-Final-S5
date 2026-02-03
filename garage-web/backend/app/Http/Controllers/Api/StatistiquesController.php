<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatistiquesController extends Controller
{
    public function index()
    {
        // Données simulées pour le test
        return response()->json([
            'totalMontant' => 12540,
            'totalClients' => 28,
            'reparationsEnCours' => 3,
            'revenusMensuels' => 4200
        ]);
    }
}