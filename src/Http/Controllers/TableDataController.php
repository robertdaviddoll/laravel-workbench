<?php

namespace RobertDavidDoll\LaravelWorkbench\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class TableDataController extends Controller
{
    public function index(Request $request, $connection, $tableName)
    {
        if (!config("database.connections.{$connection}")) {
            abort(404, "Connection not found.");
        }

        try {
            if (method_exists(Schema::connection($connection), 'getColumns')) {
                $columns = collect(Schema::connection($connection)->getColumns($tableName))->pluck('name')->toArray();
            } else {
                $columns = Schema::connection($connection)->getColumnListing($tableName);
            }
        } catch (\Exception $e) {
            $columns = [];
        }

        $query = DB::connection($connection)->table($tableName);

        // Simple search logic
        $search = $request->input('search');
        if ($search && !empty($columns)) {
            $query->where(function($q) use ($columns, $search) {
                foreach ($columns as $col) {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
            });
        }

        $rows = $query->paginate(15);

        return view('workbench::data.index', [
            'connectionName' => $connection,
            'tableName' => $tableName,
            'columns' => $columns,
            'rows' => $rows,
            'search' => $search,
        ]);
    }

    protected function getColumnsInfo($connection, $tableName)
    {
        try {
            if (method_exists(Schema::connection($connection), 'getColumns')) {
                return collect(Schema::connection($connection)->getColumns($tableName))->map(fn($c) => ['name' => $c['name'], 'type' => $c['type']])->toArray();
            } else {
                return collect(Schema::connection($connection)->getColumnListing($tableName))->map(fn($c) => ['name' => $c, 'type' => 'string'])->toArray();
            }
        } catch (\Exception $e) {
            return [];
        }
    }

    public function create($connection, $tableName)
    {
        return view('workbench::data.form', [
            'connectionName' => $connection,
            'tableName' => $tableName,
            'columns' => $this->getColumnsInfo($connection, $tableName),
            'row' => null,
            'isEdit' => false
        ]);
    }

    public function store(Request $request, $connection, $tableName)
    {
        $data = $request->except(['_token', '_method']);
        DB::connection($connection)->table($tableName)->insert($data);
        return redirect()->route('workbench.data.index', [$connection, $tableName])->with('success', 'Record created!');
    }

    public function edit($connection, $tableName, $id)
    {
        $row = DB::connection($connection)->table($tableName)->where('id', $id)->first();
        if (!$row) abort(404);

        return view('workbench::data.form', [
            'connectionName' => $connection,
            'tableName' => $tableName,
            'columns' => $this->getColumnsInfo($connection, $tableName),
            'row' => $row,
            'isEdit' => true,
            'id' => $id
        ]);
    }

    public function update(Request $request, $connection, $tableName, $id)
    {
        $data = $request->except(['_token', '_method']);
        DB::connection($connection)->table($tableName)->where('id', $id)->update($data);
        return redirect()->route('workbench.data.index', [$connection, $tableName])->with('success', 'Record updated!');
    }

    public function destroy($connection, $tableName, $id)
    {
        DB::connection($connection)->table($tableName)->where('id', $id)->delete();
        return redirect()->route('workbench.data.index', [$connection, $tableName])->with('success', 'Record deleted!');
    }
}
