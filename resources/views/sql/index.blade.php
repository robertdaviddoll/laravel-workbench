@extends('workbench::layout')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">SQL Playground</h2>
            <p class="text-gray-600 font-medium">Execute raw SQL queries against your databases.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow border-t-4 border-indigo-600 p-6 mb-8 max-w-5xl">
        <form method="POST" action="{{ route('workbench.sql.execute', $currentConnection) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Connection</label>
                <select name="connection_override" onchange="window.location.href='{{ route('workbench.sql.index', '') }}/' + this.value" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-64 sm:text-sm p-2 border bg-white">
                    @foreach($connections as $name => $config)
                        <option value="{{ $name }}" {{ $name === $currentConnection ? 'selected' : '' }}>{{ $name }} ({{ $config['driver'] ?? 'unknown' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">SQL Query</label>
                <textarea name="query" rows="6" placeholder="SELECT * FROM users LIMIT 10;" class="font-mono bg-gray-900 text-green-400 w-full rounded-md shadow-sm border-gray-300 p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ $query }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md shadow-sm transition">Execute Query</button>
            </div>
        </form>
    </div>

    @if($error)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8">
            <h3 class="text-red-800 font-bold mb-1">Query Error</h3>
            <p class="text-red-700 font-mono text-sm break-all">{{ $error }}</p>
            <p class="text-red-600 text-xs mt-2">Execution time: {{ $executionTime }}ms</p>
        </div>
    @elseif(is_array($results))
        <div class="bg-white rounded-lg shadow border-t-4 border-green-500 p-6 overflow-hidden">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-800">Results <span class="text-sm font-normal text-gray-500">({{ count($results) }} rows)</span></h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded border">{{ $executionTime }} ms</span>
            </div>
            
            <div class="overflow-x-auto border border-gray-200 rounded-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(count($results) > 0)
                                @foreach(array_keys($results[0]) as $col)
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 tracking-wider break-normal whitespace-nowrap">{{ $col }}</th>
                                @endforeach
                            @else
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Result</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($results as $row)
                        <tr class="hover:bg-gray-50">
                            @foreach($row as $val)
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700 font-mono {{ is_null($val) ? 'text-gray-400 italic' : '' }}">
                                    {{ is_null($val) ? 'NULL' : \Illuminate\Support\Str::limit((string)$val, 50) }}
                                </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">Query executed successfully, no data returned.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
