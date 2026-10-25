<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('workbench.path', 'workbench'))
    ->middleware(config('workbench.middleware', ['web']))
    ->group(function () {
        Route::get('/', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\DashboardController::class, 'index'])->name('workbench.dashboard');
        Route::get('/{connection}/tables', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\ConnectionController::class, 'index'])->name('workbench.connection.tables');
        Route::get('/{connection}/tables/{table}/schema', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\ConnectionController::class, 'schema'])->name('workbench.connection.schema');
        Route::get('/{connection}/tables/{table}/data', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\TableDataController::class, 'index'])->name('workbench.data.index');
        Route::get('/{connection}/tables/{table}/data/create', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\TableDataController::class, 'create'])->name('workbench.data.create');
        Route::post('/{connection}/tables/{table}/data', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\TableDataController::class, 'store'])->name('workbench.data.store');
        Route::get('/{connection}/tables/{table}/data/{id}/edit', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\TableDataController::class, 'edit'])->name('workbench.data.edit');
        Route::put('/{connection}/tables/{table}/data/{id}', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\TableDataController::class, 'update'])->name('workbench.data.update');
        Route::delete('/{connection}/tables/{table}/data/{id}', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\TableDataController::class, 'destroy'])->name('workbench.data.destroy');

        Route::get('/sql/{connection?}', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\SqlPlaygroundController::class, 'index'])->name('workbench.sql.index');
        Route::post('/sql/{connection}', [\RobertDavidDoll\LaravelWorkbench\Http\Controllers\SqlPlaygroundController::class, 'execute'])->name('workbench.sql.execute');
    });
