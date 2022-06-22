<?php

namespace VincentBean\HorizonDashboard\Http\Controllers;

use Illuminate\Routing\Controller;

class BatchController extends Controller
{
    public function index()
    {
        return view('horizondashboard::batch-list');
    }

    public function show(string $id)
    {
        return view('horizondashboard::batch', ['id' => $id]);
    }
}
