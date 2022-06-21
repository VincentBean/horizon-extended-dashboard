<?php

namespace VincentBean\HorizonDashboard\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('horizondashboard::dashboard');
    }
}
