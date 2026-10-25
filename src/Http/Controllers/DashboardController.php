<?php

namespace RobertDavidDoll\LaravelWorkbench\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $connections = config('database.connections', []);
        
        $availableConnections = [];
        foreach ($connections as $name => $config) {
            $availableConnections[$name] = [
                'driver' => $config['driver'] ?? 'unknown',
                'database' => $config['database'] ?? 'unknown',
            ];
        }

        return view('workbench::dashboard', [
            'connections' => $availableConnections
        ]);
    }
}
