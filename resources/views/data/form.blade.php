@extends('workbench::layout')

@section('content')
    <div class="mb-6 flex justify-between items-center whitespace-nowrap">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $isEdit ? 'Edit' : 'Create' }} Record in {{ $tableName }}</h2>
            <p class="text-gray-600 font-medium">Connection: <span class="text-indigo-600">{{ $connectionName }}</span></p>
        </div>
        <div>
            <a href="{{ route('workbench.data.index', ['connection' => $connectionName, 'table' => $tableName]) }}" class="text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md px-4 py-2 bg-white text-sm font-medium shadow-sm">Back to Data</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow border-t-4 border-indigo-600 p-6 max-w-3xl">
        <form action="{{ $isEdit ? route('workbench.data.update', [$connectionName, $tableName, $id]) : route('workbench.data.store', [$connectionName, $tableName]) }}" method="POST">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="space-y-4">
                @foreach($columns as $col)
                    @php 
                        $val = $isEdit ? ($row->{$col['name']} ?? '') : ''; 
                        $isId = strtolower($col['name']) === 'id';
                    @endphp
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $col['name'] }} <span class="text-gray-400 text-xs">({{ $col['type'] }})</span></label>
                        @if($isId && $isEdit)
                            <input type="text" name="{{ $col['name'] }}" value="{{ $val }}" readonly class="bg-gray-100 border-gray-300 rounded-md shadow-sm block w-full sm:text-sm p-2 border cursor-not-allowed text-gray-500">
                        @else
                            @if(str_contains(strtolower($col['type']), 'text') || str_contains(strtolower($col['type']), 'json'))
                                <textarea name="{{ $col['name'] }}" rows="4" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm p-2 border">{{ $val }}</textarea>
                            @else
                                <input type="text" name="{{ $col['name'] }}" value="{{ $val }}" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm p-2 border">
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('workbench.data.index', [$connectionName, $tableName]) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none flex-shrink-0">Cancel</a>
                <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none flex-shrink-0">
                    {{ $isEdit ? 'Save Changes' : 'Create Record' }}
                </button>
            </div>
        </form>
    </div>
@endsection
