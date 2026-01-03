<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    @section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-semibold mb-4">HR4 Sync Results</h1>

        @if(!empty($success) && $success === false)
            <div class="bg-red-100 p-4 rounded mb-4">
                <strong>Error:</strong> {{ $message ?? 'Unknown error' }}
            </div>
            <a href="{{ url('/dashboard') }}" class="inline-block mt-2 text-indigo-600">← Back to dashboard</a>
            @return
        @endif

        @if(!empty($no_changes) && $no_changes)
            <div class="bg-green-100 p-4 rounded mb-4">
                <strong>No changes:</strong> The database is already synchronized with HR4.
            </div>
        @else
            <div class="bg-green-50 p-4 rounded mb-4">
                <p><strong>Summary:</strong></p>
                <ul>
                    <li>Created: {{ $created ?? 0 }}</li>
                    <li>Updated: {{ $updated ?? 0 }}</li>
                    <li>Skipped: {{ $skipped ?? 0 }}</li>
                    <li>Errors: {{ $errors ?? 0 }}</li>
                </ul>
            </div>
        @endif

        {{-- Terminated listed by the API --}}
        <h2 class="text-xl font-medium mt-6 mb-2">Employees marked as <em>Terminated</em> (from HR4)</h2>

        @if(!empty($terminated_from_api) && count($terminated_from_api))
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">Employee ID</th>
                        <th class="p-2 border">Name</th>
                        <th class="p-2 border">Email</th>
                        <th class="p-2 border">Employment Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($terminated_from_api as $t)
                        <tr>
                            <td class="p-2 border">{{ $t['employee_id'] ?? '-' }}</td>
                            <td class="p-2 border">{{ $t['full_name'] ?? ($t['firstname'] ?? '-') }}</td>
                            <td class="p-2 border">{{ $t['email'] ?? '-' }}</td>
                            <td class="p-2 border">{{ $t['employment_status'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-3 text-gray-600">No terminated employees returned by HR4.</div>
        @endif

        {{-- Employees changed to Terminated during this sync --}}
        <h2 class="text-xl font-medium mt-6 mb-2">Employees newly set to <em>Terminated</em> (this sync)</h2>

        @if(!empty($terminated_now_updated) && count($terminated_now_updated))
            <ul class="list-disc pl-6">
                @foreach($terminated_now_updated as $t)
                    <li>
                        {{ $t['employee_id'] }} — {{ $t['name'] ?? '-' }} ({{ $t['email'] ?? '-' }})
                        — {{ $t['old_status'] ?? 'N/A' }} → {{ $t['new_status'] }}
                    </li>
                @endforeach
            </ul>
        @else
            <div class="p-3 text-gray-600">No users were changed to Terminated during this sync.</div>
        @endif

        <div class="mt-6">
            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-gray-200 rounded">← Back to dashboard</a>
        </div>
    </div>
@endsection
</x-app-layout>
