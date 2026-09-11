<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ArxSocController extends Controller
{
    public function show(): View
    {
        /**
         * Récupération des datas depuis /App/Data/soc.php
         */
        $soc_data = require app_path("Data/soc.php");
        /**
         * Récupération de la vue et des datas
         */
        return view('arx.soc', compact('soc_data'));
    }
}
