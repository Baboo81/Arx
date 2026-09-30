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
     * Création d'une méthode publique nommée ask, qui reçoit un $prompt obligatoirement sous forme de string, et qui promet de retourner une string
     */
    public function ask(string $prompt): string
    {
        return "ARX AI a bien reçu la demande ! : {$prompt}";
    }
}