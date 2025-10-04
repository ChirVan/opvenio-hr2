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
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold">Role Mapping</h2>
                    <a href="{{ route('competency.rolemapping.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Role Mapping
                    </a>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-green-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Role</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Framework</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Competency</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Level</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Status</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-green-800 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($roleMappings as $mapping)
                            <tr>
                                <td class="px-4 py-2">{{ $mapping->role_name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $mapping->framework->framework_name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $mapping->competency->competency_name ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $level = strtolower($mapping->proficiency_level ?? '');
                                        $levelColors = [
                                            'beginner' => 'bg-green-100 text-green-800',
                                            'intermediate' => 'bg-yellow-100 text-yellow-800',
                                            'expert' => 'bg-blue-100 text-blue-800',
                                        ];
                                        $colorClass = $levelColors[$level] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-block {{ $colorClass }} px-2 py-1 rounded text-xs">
                                        {{ ucfirst($mapping->proficiency_level ?? '-') }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    @if($mapping->status === 'active')
                                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>
                                    @elseif($mapping->status === 'inactive')
                                        <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Inactive</span>
                                    @else
                                        <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">{{ ucfirst($mapping->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <a href="competency.rolemapping.edit', $mapping->id) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                        <i class='bx bx-edit-alt text-lg align-middle'></i>
                                    </a>
                                    <a href="{{ route('competency.rolemapping.show', $mapping->id) }}" class="text-gray-600 hover:text-gray-800 mr-2" title="View">
                                        <i class='bx bx-show text-lg align-middle'></i>
                                    </a>
                                    <form action="{{ route('competency.rolemapping.destroy', $mapping->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
                                <td colspan="6" class="px-4 py-2 text-center text-gray-500">No role mappings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>