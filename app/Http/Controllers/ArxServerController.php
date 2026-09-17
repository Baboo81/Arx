<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ArxServerController extends Controller
{
    public function show(): View
    {
        /**
         * On récupère les datas depuis les datas depuis le fichier : App/Data/server.php
         */
        $server_data = require app_path("Data/server.php");

        /**
         * Récupération de la vue + datas
         */
        return view('arx.server', compact('server_data'));
    }
}
