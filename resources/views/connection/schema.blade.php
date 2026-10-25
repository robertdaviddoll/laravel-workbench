@extends('workbench::layout')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $tableName }} Schema</h2>
            <p class="text-gray-600 font-medium">Connection: <span class="text-indigo-600">{{ $connectionName }}</span></p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('workbench.connection.tables', $connectionName) }}" class="text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md px-4 py-2 bg-white text-sm font-medium shadow-sm">Back to Tables</a>
        </div>
    </div>

    <!-- Columns -->
    <div class="bg-white rounded-lg shadow border-t-4 border-indigo-600 p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Columns</h3>
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nullable</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Default</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($columns as $col)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ is_array($col) ? $col['name'] : $col->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-pink-600">{{ is_array($col) ? $col['type'] : $col->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php $isNullable = is_array($col) ? $col['nullable'] ?? false : $col->nullable ?? false; @endphp
                            @if($isNullable)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ is_array($col) ? ($col['default'] ?? 'NULL') : ($col->default ?? 'NULL') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No columns found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
