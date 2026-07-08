<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        /**
        * On récupère les datas depuis le fichier : App/Data/home.php 
        */
        $home_data = require app_path("Data/home.php");

        /**
         * Onretourne la vue avec les datas 
         */
        return view('home', compact('home_data'));
    }
    
}
