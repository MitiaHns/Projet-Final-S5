<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ReparationsController extends Controller
{
    public function index()
    {
        $reparations = [
            [
                'id' => 1,
                'client_nom' => 'Jean Dupont',
                'client_email' => 'jean.dupont@email.com',
                'marque' => 'Renault',
                'modele' => 'Clio',
                'immatriculation' => 'AB-123-CD',
                'description_panne' => 'Freins qui grincent',
                'statut' => 'en_cours',
                'slot' => 1,
                'montant_total' => 320.00,
                'date_creation' => '2024-01-15',
                'interventions' => ['frein', 'vidange']
            ],
            [
                'id' => 2,
                'client_nom' => 'Marie Martin',
                'client_email' => 'marie.martin@email.com',
                'marque' => 'Peugeot',
                'modele' => '208',
                'immatriculation' => 'EF-456-GH',
                'description_panne' => 'Vidange nécessaire',
                'statut' => 'en_cours',
                'slot' => 2,
                'montant_total' => 180.00,
                'date_creation' => '2024-01-16',
                'interventions' => ['vidange', 'filtre']
            ],
            [
                'id' => 3,
                'client_nom' => 'Pierre Bernard',
                'client_email' => 'pierre.bernard@email.com',
                'marque' => 'Citroën',
                'modele' => 'C3',
                'immatriculation' => 'IJ-789-KL',
                'description_panne' => 'Changement pneus',
                'statut' => 'en_attente',
                'slot' => null,
                'montant_total' => 450.00,
                'date_creation' => '2024-01-17',
                'interventions' => ['pneu']
            ]
        ];

        return response()->json($reparations);
    }
}