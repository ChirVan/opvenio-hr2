<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none'">
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none'">
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                @endif

            <!-- Table -->
            <div class="bg-white shadow rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <!-- Toolbar row: search + create -->
                    <tr>
                        <th colspan="6" class="px-6 py-3 bg-white">
                            <div class="flex items-center justify-between">
                                <form class="w-full max-w-md" method="GET" action="{{ route('competency.frameworks') }}">
                                    <div class="relative">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
                                            <circle cx="11" cy="11" r="6" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <input
                                            type="text"
                                            name="search"
                                            value="{{ request('search') }}"
                                            placeholder="Search frameworks..."
                                            class="pl-10 pr-4 py-2 w-full rounded-md border border-gray-200 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400 transition"
                                        />
                                    </div>
                                </form>
                                
                                <!-- Create Framework Button -->
                                <a href="{{ route('competency.frameworks.create') }}" class="ml-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-colors duration-200 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Framework
                                </a>
                            </div>
                        </th>
                    </tr>

                    <tr class="bg-green-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Framework Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Effective Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-green-800 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($frameworks as $index => $framework)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $framework->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $framework->framework_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ Str::limit($framework->description, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $framework->effective_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="{{ $framework->status_color }}">{{ ucfirst($framework->status) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('competency.frameworks.edit', $framework) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                        <!-- Boxicons Edit Icon -->
                                        <i class='bx bx-edit-alt text-xl align-middle'></i>
                                    </a>
                                    <form method="POST" action="{{ route('competency.frameworks.destroy', $framework) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this framework?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <!-- Boxicons Trash Icon -->
                                            <i class='bx bx-trash text-xl align-middle'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    No frameworks found. <a href="{{ route('competency.frameworks.create') }}" class="text-green-600 hover:underline">Create your first framework</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white shadow rounded-lg overflow-x-auto mt-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <!-- Toolbar row: search + create -->
                    <tr>
                        <th colspan="7" class="px-6 py-3 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <form class="w-full max-w-md" method="GET" action="{{ route('competency.frameworks') }}">
                                        <div class="relative">
                                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
                                                <circle cx="11" cy="11" r="6" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <input
                                                type="text"
                                                name="competency_search"
                                                value="{{ request('competency_search') }}"
                                                placeholder="Search competencies..."
                                                class="pl-9 pr-4 py-2 w-full rounded-md border border-gray-200 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-sm"
                                            />
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Create Competency Button -->
                                <a href="{{ route('competency.competencies.create') }}" class="ml-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-colors duration-200 shadow-sm text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Competency
                                </a>
                            </div>
                        </th>
                    </tr>

                    <tr class="bg-blue-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Competency Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Framework</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Proficiency Levels</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-blue-800 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($competencies as $index => $competency)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $competency->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $competency->competency_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $competency->framework_badge_color }}">
                                        {{ $competency->framework->framework_name ?? 'No Framework' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($competency->description, 60) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="text-blue-600">{{ $competency->proficiency_levels }} Levels</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="{{ $competency->status_color }} text-sm">{{ ucfirst($competency->status) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('competency.competencies.edit', $competency) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                        <i class='bx bx-edit-alt text-lg align-middle'></i>
                                    </a>
                                    <a href="{{ route('competency.competencies.show', $competency) }}" class="text-gray-600 hover:text-gray-800 mr-2" title="View Details">
                                        <i class='bx bx-show text-lg align-middle'></i>
                                    </a>
                                    <form method="POST" action="{{ route('competency.competencies.destroy', $competency) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this competency?')">
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
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    No competencies found. <a href="{{ route('competency.competencies.create') }}" class="text-blue-600 hover:underline">Create your first competency</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>