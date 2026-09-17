<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ArxVpnController extends Controller
{
    public function show(): View
    {
        /**
         * Récupération des datas dans le fichier  : App/Data/vpn.php
         */
        $vpn_data = require app_path("Data/vpn.php");

        /**
         * Récupération de la vue avec les datas 
         */
        return view('arx.vpn', compact('vpn_data'));
    }
}
