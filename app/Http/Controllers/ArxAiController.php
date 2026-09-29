<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ArxAiController extends Controller
{
    public function show(): View
    {
        /**
         * On récupère les datas depuis le fichier : App/Data/ai.php
         */
        $ai_data = require app_path("Data/ai.php");
        
        /**
         * Récupèration de la vue avec les datas 
         */
        return view('arx.ai', compact('ai_data'));
    }

    public function ask(Request $request)
    {
        /**
         * $request est l'objet Laravel qui représente la requête reçue du navigateur.
         * $validated contient les données à valider, Laravel dit : je cherche prompt et il doit être un string de max 2000 caractères et si tout est bon met tout dans $validated
         */
        $validated = $request->validate([
            'prompt' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        /**
         * Extraction du prompt
         */
        $prompt = $validated['prompt'];
        /**
         * Réponse ARX AI
         */
        $response = "ARX AI a bien reçu ta demande ! : {$prompt}";

        $ai_data = require app_path("Data/ai.php");

        return view('arx.ai', compact('ai_data', 'prompt', 'response'));
    }
}

