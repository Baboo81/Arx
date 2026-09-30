<?php

namespace App\Http\Controllers;

use App\Services\ArxAiService;
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

    public function ask(Request $request, ArxAiService $arxAiService)
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
         * Demande au service ARX AI de traiter $prompt, puis on place ce qu'il me retourne dans $response
         */
        $response = $arxAiService->ask($prompt);

        $ai_data = require app_path("Data/ai.php");

        return view('arx.ai', compact('ai_data', 'prompt', 'response'));
    }
}

