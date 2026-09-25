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
        $validated = $request->validate([
            'prompt' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

    }
}
