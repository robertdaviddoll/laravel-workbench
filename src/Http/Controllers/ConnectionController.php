<?php

namespace RobertDavidDoll\LaravelWorkbench\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;

class ConnectionController extends Controller
{
    public function index($connection)
    {
        if (!config("database.connections.{$connection}")) {
            abort(404, "Connection not found.");
        }

        try {
            $tables = Schema::connection($connection)->getTables();
        } catch (\Exception $e) {
            return back()->with('error', "Failed to connect to '{$connection}'. " . $e->getMessage());
        }
        
        return view('workbench::connection.tables', [
            'connectionName' => $connection,
            'tables' => $tables,
        ]);
    }

    public function schema($connection, $tableName)
    {
        if (!config("database.connections.{$connection}")) {
            abort(404, "Connection not found.");
        }

        try {
            if (method_exists(Schema::connection($connection), 'getColumns')) {
                $columns = Schema::connection($connection)->getColumns($tableName);
            } else {
                $columnsRaw = Schema::connection($connection)->getColumnListing($tableName);
                $columns = collect($columnsRaw)->map(fn($col) => [
                    'name' => $col, 
                    'type' => 'Unknown (requires DBAL in older Laravel)', 
                    'nullable' => false, 
                    'default' => null
                ])->toArray();
            }
        } catch (\Exception $e) {
            $columns = [];
        }

        return view('workbench::connection.schema', [
            'connectionName' => $connection,
            'tableName' => $tableName,
            'columns' => $columns,
        ]);
    }
}
