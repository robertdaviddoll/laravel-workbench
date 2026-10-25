@extends('workbench::layout')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Connection: {{ $connectionName }}</h2>
            <p class="text-gray-600 font-medium">Available tables in this database.</p>
        </div>
        <a href="{{ route('workbench.dashboard') }}" class="text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md px-4 py-2 bg-white text-sm font-medium shadow-sm">Back to Dashboard</a>
    </div>

    <div class="bg-white rounded-lg shadow border-t-4 border-indigo-600 p-6">
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Table Name</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tables as $table)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $table['name'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('workbench.data.index', ['connection' => $connectionName, 'table' => $table['name']]) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md text-xs font-semibold">View Data</a>
                            <a href="{{ route('workbench.connection.schema', ['connection' => $connectionName, 'table' => $table['name']]) }}" class="text-gray-600 hover:text-gray-900 bg-gray-50 px-3 py-1 rounded-md text-xs font-semibold ml-2">Schema</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No tables found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
