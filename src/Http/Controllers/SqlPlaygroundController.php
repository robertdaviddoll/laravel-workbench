<?php

namespace RobertDavidDoll\LaravelWorkbench\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SqlPlaygroundController extends Controller
{
    public function index($connection = null)
    {
        $connections = config('database.connections', []);
        $connectionName = $connection ?? config('database.default');

        return view('workbench::sql.index', [
            'connections' => $connections,
            'currentConnection' => $connectionName,
            'query' => '',
            'results' => null,
            'error' => null,
            'executionTime' => 0
        ]);
    }

    public function execute(Request $request, $connection)
    {
        if (!config("database.connections.{$connection}")) {
            abort(404, "Connection not found.");
        }

        $query = $request->input('query');
        $results = null;
        $error = null;
        $executionTime = 0;

        if ($query) {
            $start = microtime(true);
            try {
                if (preg_match('/^\s*(select|show|describe|explain|pragma)/i', $query)) {
                    $results = DB::connection($connection)->select($query);
                } else {
                    $affected = DB::connection($connection)->statement($query);
                    $results = [['Rows Affected' => $affected]];
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
            $executionTime = round((microtime(true) - $start) * 1000, 2);
        }

        $connections = config('database.connections', []);

        return view('workbench::sql.index', [
            'connections' => $connections,
            'currentConnection' => $connection,
            'query' => $query,
            'results' => $results ? json_decode(json_encode($results), true) : null,
            'error' => $error,
            'executionTime' => $executionTime
        ]);
    }
}
