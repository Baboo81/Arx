<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ArxServerController extends Controller
{
    public function show(): View
    {
        return view('arx.server');
    }
}
