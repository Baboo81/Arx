<?php

/**
 * Indique la localisation de la classe, grâce à cela Composer peut associer App\Services\ArxAiService.php
 */
namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * La classe ArxAiService sera le point de communication entre Laravel | ARX Core | ARX AI
 */
Class ArxAiService
{
    /**
     * Reçoit un prompt sous forme de string et retourne une réponse ARX AI structurée sous forme de tableau.
     */
    public function ask(string $prompt): array
    {
        $requestData = [
           'type' => 'conversation',
           'prompt' => $prompt,
           'context' => [
                'source' => 'arx-core',
           ], 
        ];
 
       $response = Http::post(
            'http://127.0.0.1:8001/ask',
            $requestData
       );
        
       return $response->json();
       
    }
}