<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class InterventionsController extends Controller
{
    public function index()
    {
        $interventions = [
            ['id' => 1, 'type' => 'frein', 'nom' => 'Plaquettes de frein', 'prix' => 120.00, 'duree_secondes' => 3600, 'couleur' => '#dc3545'],
            ['id' => 2, 'type' => 'vidange', 'nom' => 'Vidange complète', 'prix' => 80.00, 'duree_secondes' => 1800, 'couleur' => '#0d6efd'],
            ['id' => 3, 'type' => 'filtre', 'nom' => 'Filtre à air', 'prix' => 30.00, 'duree_secondes' => 900, 'couleur' => '#198754'],
            ['id' => 4, 'type' => 'batterie', 'nom' => 'Batterie', 'prix' => 150.00, 'duree_secondes' => 1800, 'couleur' => '#ffc107'],
            ['id' => 5, 'type' => 'amortisseur', 'nom' => 'Amortisseurs', 'prix' => 300.00, 'duree_secondes' => 7200, 'couleur' => '#6f42c1'],
            ['id' => 6, 'type' => 'embrayage', 'nom' => 'Embrayage', 'prix' => 500.00, 'duree_secondes' => 10800, 'couleur' => '#fd7e14'],
            ['id' => 7, 'type' => 'pneu', 'nom' => 'Pneus (x4)', 'prix' => 200.00, 'duree_secondes' => 2400, 'couleur' => '#20c997'],
            ['id' => 8, 'type' => 'refroidissement', 'nom' => 'Système de refroidissement', 'prix' => 250.00, 'duree_secondes' => 5400, 'couleur' => '#0dcaf0']
        ];

        return response()->json($interventions);
    }
}