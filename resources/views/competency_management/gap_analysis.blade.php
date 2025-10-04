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
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Gap Analysis</h2>
                    <div class="flex items-center space-x-3">
                        <button onclick="refreshData()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm" title="Refresh employee data">
                            <i class='bx bx-refresh'></i> Refresh
                        </button>
                        <a href="{{ route('competency.gapanalysis.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            <i class='bx bx-plus'></i> Add Gap Analysis
                        </a>
                    </div>
                </div>
                
                <!-- API Status Indicator -->
                @php
                    $apiStatus = app(App\Services\EmployeeApiService::class)->getEmployees();
                @endphp
                
                @if($apiStatus === null)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class='bx bx-error-circle text-yellow-400 text-xl'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>API Connection Issue:</strong> Unable to fetch employee data from external API. 
                                    Displaying gap analysis with limited employee information.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class='bx bx-check-circle text-green-400 text-xl'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    <strong>API Connected:</strong> Successfully loaded {{ count($apiStatus) }} employees from external API.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="hidden text-center py-4">
                    <div class="inline-flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-green-600 mr-2"></div>
                        <span class="text-sm text-gray-600">Loading employee data...</span>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-green-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Employee ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Employee Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Job Title</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Employment Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Competency</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Level</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-green-800 uppercase">Notes</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-green-800 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($gapAnalyses as $gap)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $gap->employee_id_display }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $gap->employee_full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $gap->employee_email }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $gap->job_title }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $gap->employment_status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $gap->employment_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $gap->competency_name ?? $gap->competency_id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $gap->proficiency_level }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ Str::limit($gap->notes, 30) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('competency.gapanalysis.show', $gap->id) }}" 
                                           class="text-gray-600 hover:text-gray-800 transition-colors" title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                        <a href="{{ route('competency.gapanalysis.edit', $gap->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                            <i class='bx bx-edit-alt text-lg'></i>
                                        </a>
                                        <form action="{{ route('competency.gapanalysis.destroy', $gap->id) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this gap analysis record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Delete">
                                                <i class='bx bx-trash text-lg'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class='bx bx-data text-4xl text-gray-300 mb-2'></i>
                                        <p class="text-sm">No gap analysis records found.</p>
                                        <p class="text-xs text-gray-400 mt-1">Click "Add Gap Analysis" to create your first record.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript for enhanced functionality -->
    <script>
        function refreshData() {
            const loadingIndicator = document.getElementById('loadingIndicator');
            const refreshButton = document.querySelector('button[onclick="refreshData()"]');
            
            // Show loading state
            loadingIndicator.classList.remove('hidden');
            refreshButton.disabled = true;
            refreshButton.innerHTML = '<i class="bx bx-loader-alt animate-spin"></i> Refreshing...';
            
            // Make AJAX request to refresh data
            fetch('{{ route("competency.gapanalysis") }}?refresh=1', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    throw new Error('Failed to refresh data');
                }
            })
            .catch(error => {
                console.error('Error refreshing data:', error);
                alert('Failed to refresh employee data. Please try again.');
            })
            .finally(() => {
                // Reset loading state
                loadingIndicator.classList.add('hidden');
                refreshButton.disabled = false;
                refreshButton.innerHTML = '<i class="bx bx-refresh"></i> Refresh';
            });
        }

        // Auto-refresh every 5 minutes
        setInterval(() => {
            refreshData();
        }, 300000); // 5 minutes = 300,000ms
    </script>
</x-app-layout>