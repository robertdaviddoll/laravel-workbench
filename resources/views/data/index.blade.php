@extends('workbench::layout')

@section('content')
    <div class="mb-6 flex justify-between items-center whitespace-nowrap">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $tableName }} Data</h2>
            <p class="text-gray-600 font-medium">Connection: <span class="text-indigo-600">{{ $connectionName }}</span></p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('workbench.data.create', [$connectionName, $tableName]) }}" class="text-white bg-indigo-600 hover:bg-indigo-700 rounded-md px-4 py-2 text-sm font-medium shadow-sm">Add Record</a>
            <a href="{{ route('workbench.connection.tables', $connectionName) }}" class="text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md px-4 py-2 bg-white text-sm font-medium shadow-sm">Back to Tables</a>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <form method="GET" action="{{ route('workbench.data.index', ['connection' => $connectionName, 'table' => $tableName]) }}" class="flex">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search all columns..." class="border-gray-300 rounded-l-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm p-2 border">
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 border border-l-0 border-gray-300 rounded-r-md px-4 py-2 text-gray-700 font-medium text-sm">Search</button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow border-t-4 border-indigo-600 overflow-x-auto mb-8">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 tracking-wider w-16">Actions</th>
                    @foreach($columns as $col)
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ $col }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rows as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium flex items-center space-x-2">
                        @if(isset($row->id))
                            <a href="{{ route('workbench.data.edit', [$connectionName, $tableName, $row->id]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <span class="text-gray-300">|</span>
                            <form action="{{ route('workbench.data.destroy', [$connectionName, $tableName, $row->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium bg-transparent border-0 cursor-pointer p-0">Delete</button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs">No ID</span>
                        @endif
                    </td>
                    @foreach($columns as $col)
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 truncate max-w-xs">
                            {{ \Illuminate\Support\Str::limit($row->{$col} ?? 'NULL', 50) }}
                        </td>
                    @endforeach
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($columns) + 1 }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t border-gray-200">
            {{ $rows->appends(['search' => $search])->links() }}
        </div>
    </div>
@endsection
