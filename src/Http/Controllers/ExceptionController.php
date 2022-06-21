<?php

namespace VincentBean\HorizonDashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use VincentBean\HorizonDashboard\Models\JobException;

class ExceptionController extends Controller
{
    public function index()
    {
        return view('horizondashboard::exception-list', [
                'exceptions' => JobException::query()
                    ->orderBy('occured_at')
                    ->with('jobInformation')
                    ->get()
                    ->paginate(25)
            ]
        );
    }

    public function show(int $id)
    {
        return view('horizondashboard::exception', [
            'exception' => JobException::query()
                ->with('jobInformation')
                ->findOrFail($id)
        ]);
    }
}
