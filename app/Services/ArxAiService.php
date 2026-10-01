<?php

/**
 * Indique la localisation de la classe, grâce à cela Composer peut associer App\Services\ArxAiService.php
 */
namespace App\Services;


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
            'request' => [
                'type' => 'conversation',
                'prompt' => $prompt,
            ],
            'context' => [
                'source' => 'arx-core',
            ],
        ];
 
        return [
            'status' => 'success',
            'module' => 'arx-ai',
            'prompt' =>  $prompt,
            'message' => "ARX AI a bien reçu la demande ! : {$prompt}",
        ];
    }
}