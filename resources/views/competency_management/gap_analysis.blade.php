<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-6">Gap Analysis</h2>
                <a href="{{ route('competency.gapanalysis.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mb-4 inline-block">
                    + Add Gap Analysis
                </a>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-green-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Employee</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Competency</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Framework</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Level</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Description</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-green-800 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($gapAnalyses as $gap)
                            <tr>
                                <td>{{ $gap->employee_lastname }}, {{ $gap->employee_firstname }}</td>
                                <td class="px-4 py-2">{{ $gap->competency_name ?? $gap->competency_id }}</td>
                                <td class="px-4 py-2">{{ $gap->framework }}</td>
                                <td class="px-4 py-2">{{ $gap->proficiency_level }}</td>
                                <td class="px-4 py-2">{{ $gap->notes }}</td>
                                <td class="px-4 py-2 text-right">
                                    <a href="{{ route('competency.gapanalysis.show', $gap->id) }}" class="text-gray-600 hover:text-gray-800 mr-2" title="View">
                                        <i class='bx bx-show text-lg align-middle'></i>
                                    </a>
                                    <a href="{{ route('competency.gapanalysis.edit', $gap->id) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                        <i class='bx bx-edit-alt text-lg align-middle'></i>
                                    </a>
                                    <form action="{{ route('competency.gapanalysis.destroy', $gap->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <i class='bx bx-trash text-lg align-middle'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-2 text-center text-gray-500">No gap analysis records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>