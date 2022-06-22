<?php

namespace VincentBean\HorizonDashboard\Http\Controllers;

use Illuminate\Routing\Controller;

class JobController extends Controller
{
    public function index(string $type, ?string $queue = null)
    {
        return view('horizondashboard::job-list', ['type' => $type, 'queue' => $queue ?? '']);
    }

    public function show(string $id)
    {
        return view('horizondashboard::job', ['id' => $id]);
    }
}
