<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg mb-6 shadow-sm animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-green-800 font-medium">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700 transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Gap Analysis Management</h1>
                <p class="mt-2 text-sm text-gray-600">Track and manage employee competency assessments</p>
            </div>

            <!-- API Status Indicator -->
            @php
                $apiStatus = app(App\Services\EmployeeApiService::class)->getEmployees();
            @endphp
            
            @if($apiStatus === null)
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-yellow-800">API Connection Issue</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                Unable to fetch employee data from external API. Displaying gap analysis with limited employee information.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-green-800">API Connected</h3>
                            <p class="mt-1 text-sm text-green-700">
                                Successfully loaded {{ count($apiStatus) }} employees from external API.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Employee Assessment Integration -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Employee Assessment Integration</h3>
                        <p class="text-sm text-gray-600">Sync employee data and manage competency assessments</p>
                    </div>
                    <button onclick="syncEmployees()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition-all duration-200">
                        <i class='bx bx-sync mr-2'></i>
                        Sync Employees
                    </button>
                </div>

                <!-- Employee Stats Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class='bx bx-user text-2xl text-blue-600'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-800">Total Employees</p>
                                <p class="text-2xl font-bold text-blue-900" id="totalEmployees">
                                    {{ $apiStatus ? count($apiStatus) : 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class='bx bx-check-circle text-2xl text-green-600'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">Assessed</p>
                                <p class="text-2xl font-bold text-green-900" id="assessedEmployees">
                                    {{ $gapAnalyses->unique('employee_id_display')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-4 rounded-lg border border-yellow-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class='bx bx-time text-2xl text-yellow-600'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-800">Pending</p>
                                <p class="text-2xl font-bold text-yellow-900" id="pendingEmployees">
                                    {{ $apiStatus ? count($apiStatus) - $gapAnalyses->unique('employee_id_display')->count() : 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-4 rounded-lg border border-purple-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class='bx bx-trending-up text-2xl text-purple-600'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-purple-800">Coverage</p>
                                <p class="text-2xl font-bold text-purple-900" id="coveragePercentage">
                                    {{ $apiStatus && count($apiStatus) > 0 ? round(($gapAnalyses->unique('employee_id_display')->count() / count($apiStatus)) * 100) : 0 }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Imports & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Employee Imports -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Recent Employee Imports</h4>
                        <div class="space-y-2" id="recentImports">
                            <div class="text-xs text-gray-500">No recent imports available</div>
                        </div>
                    </div>

                    <!-- Quick Assessment Creation -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Quick Assessment</h4>
                        <form id="quickAssessmentForm" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-2 gap-3">
                                <select name="employee_id" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <option value="">Select Employee</option>
                                    @if($apiStatus)
                                        @foreach($apiStatus as $employee)
                                            <option value="{{ $employee['employee_id'] }}">
                                                {{ $employee['full_name'] }} ({{ $employee['employee_id'] }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <select name="competency_id" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <option value="">Select Competency</option>
                                    <!-- This would be populated from competencies table -->
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <select name="proficiency_level" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <option value="">Proficiency Level</option>
                                    <option value="1">Basic</option>
                                    <option value="2">Intermediate</option>
                                    <option value="3">Expert</option>
                                </select>
                                <button type="submit" 
                                        class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                                    <i class='bx bx-plus mr-1'></i> Add Assessment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- New Role-Based Gap Analysis Banner -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class='bx bx-target-lock text-2xl text-blue-600'></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-blue-800">New! Role-Based Gap Analysis Available</h3>
                            <p class="text-xs text-blue-700">Compare employee skills against role requirements automatically. More accurate than manual assessments.</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('competency.gap-analysis') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition-all duration-200">
                            <i class='bx bx-chart-pie mr-2'></i>
                            Try Role-Based Analysis
                        </a>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
  <form method="GET" action="{{ route('competency.gapanalysis') }}" class="flex flex-wrap items-center justify-between gap-4">
    
    <div class="flex items-center gap-4 flex-1 min-w-0">
      <!-- Search -->
      <label for="search" class="text-xs font-semibold text-gray-700 whitespace-nowrap">Search:</label>
      <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Employee, Competency, etc." class="flex-grow min-w-0 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 text-sm transition-all duration-200" />
      
      <!-- Sort -->
      <label for="sort" class="text-xs font-semibold text-gray-700 whitespace-nowrap ml-4">Sort by:</label>
      <select name="sort" id="sort" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 text-sm max-w-xs transition-all duration-200">
        <option value="recent" {{ request('sort', 'recent') == 'recent' ? 'selected' : '' }}>Most Recent</option>
        <option value="employee" {{ request('sort') == 'employee' ? 'selected' : '' }}>Employee Name</option>
        <option value="competency" {{ request('sort') == 'competency' ? 'selected' : '' }}>Competency</option>
        <option value="level" {{ request('sort') == 'level' ? 'selected' : '' }}>Level</option>
      </select>
    </div>
    
    <div class="flex items-center gap-3 flex-shrink-0">
      <!-- Filter Button -->
      <button type="submit" class="inline-flex items-center px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow transition-all duration-200 whitespace-nowrap">
        <i class='bx bx-search mr-2'></i> Filter
      </button>
      <!-- Reset Button -->
      <a href="{{ route('competency.gapanalysis') }}" class="inline-flex items-center px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200 whitespace-nowrap">
        <i class='bx bx-reset mr-2'></i> Reset
      </a>

      <!-- Add Gap Analysis Button -->
      <a href="{{ route('competency.gapanalysis.create') }}" 
         class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 whitespace-nowrap">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Add Gap Analysis
      </a>
    </div>
  </form>
</div>



            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-700">Loading employee data...</span>
                </div>
            </div>

            <!-- Gap Analysis Table -->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Employee ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Employee Name</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Job Title</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Employment Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Competency</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Level</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-green-800 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($gapAnalyses as $gap)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $gap->employee_id_display }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $gap->employee_full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $gap->employee_email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $gap->job_title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                            {{ $gap->employment_status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $gap->employment_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $gap->competency_name ?? $gap->competency_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $level = $gap->proficiency_level;
                                            if ($level == 1 || $level == '1') {
                                                $label = 'Basic';
                                                $color = 'bg-yellow-100 text-yellow-800';
                                            } elseif ($level == 2 || $level == '2') {
                                                $label = 'Intermediate';
                                                $color = 'bg-blue-100 text-blue-800';
                                            } elseif ($level == 3 || $level == '3') {
                                                $label = 'Expert';
                                                $color = 'bg-green-100 text-green-800';
                                            } else {
                                                $label = $level;
                                                $color = 'bg-gray-100 text-gray-800';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 max-w-xs">{{ Str::limit($gap->notes, 30) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('competency.gapanalysis.show', $gap->id) }}" 
                                               class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-all duration-200 transform hover:scale-110" 
                                               title="View Details">
                                                <i class='bx bx-show text-lg'></i>
                                            </a>
                                            <a href="{{ route('competency.gapanalysis.edit', $gap->id) }}" 
                                               class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-900 transition-all duration-200 transform hover:scale-110" 
                                               title="Edit Record">
                                                <i class='bx bx-edit-alt text-lg'></i>
                                            </a>
                                            <form action="{{ route('competency.gapanalysis.destroy', $gap->id) }}" 
                                                  method="POST" 
                                                  class="inline delete-gap-form" 
                                                  data-gap-id="{{ $gap->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-900 transition-all duration-200 transform hover:scale-110 delete-gap-btn" 
                                                        title="Delete" data-gap-id="{{ $gap->id }}">
                                                    <i class='bx bx-trash text-lg'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16">
                                        <div class="text-center">
                                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No gap analysis records found</h3>
                                            <p class="text-gray-500 mb-4">Get started by creating your first gap analysis record</p>
                                            <a href="{{ route('competency.gapanalysis.create') }}" 
                                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow transition-colors">
                                                <i class='bx bx-plus text-lg mr-2'></i>
                                                Add Gap Analysis
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

    <!-- JavaScript for enhanced functionality -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function refreshData() {
            const loadingIndicator = document.getElementById('loadingIndicator');
            const refreshButton = document.querySelector('button[onclick="refreshData()"]');
            // Show loading state
            loadingIndicator.classList.remove('hidden');
            refreshButton.disabled = true;
            refreshButton.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i> Refreshing...';
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
                refreshButton.innerHTML = '<i class="bx bx-refresh mr-2"></i> Refresh';
            });
        }

        // SweetAlert2 for delete confirmation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-gap-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = btn.closest('form');
                    Swal.fire({
                        title: 'Delete Gap Analysis?',
                        text: 'This action cannot be undone. Are you sure you want to delete this record?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            popup: 'rounded-lg',
                            confirmButton: 'bg-red-600 text-white font-bold px-4 py-2 rounded-lg',
                            cancelButton: 'bg-gray-200 text-gray-700 font-bold px-4 py-2 rounded-lg'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });

        // Employee Sync Functionality
        function syncEmployees() {
            const syncButton = document.querySelector('button[onclick="syncEmployees()"]');
            const originalText = syncButton.innerHTML;
            
            // Show loading state
            syncButton.disabled = true;
            syncButton.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i> Syncing...';
            
            // Make AJAX request to sync employees
            fetch('/competency/employees/sync', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Sync Complete!',
                        text: `Successfully synced ${data.count} employees from API`,
                        icon: 'success',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        updateEmployeeStats(data);
                        updateRecentImports(data.recent_imports);
                    });
                } else {
                    throw new Error(data.message || 'Sync failed');
                }
            })
            .catch(error => {
                console.error('Sync error:', error);
                Swal.fire({
                    title: 'Sync Failed',
                    text: 'Unable to sync employee data. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            })
            .finally(() => {
                // Reset button state
                syncButton.disabled = false;
                syncButton.innerHTML = originalText;
            });
        }

        // Update employee statistics on page
        function updateEmployeeStats(data) {
            if (data.stats) {
                document.getElementById('totalEmployees').textContent = data.stats.total || 0;
                document.getElementById('assessedEmployees').textContent = data.stats.assessed || 0;
                document.getElementById('pendingEmployees').textContent = data.stats.pending || 0;
                document.getElementById('coveragePercentage').textContent = (data.stats.coverage || 0) + '%';
            }
        }

        // Update recent imports list
        function updateRecentImports(imports) {
            const importsContainer = document.getElementById('recentImports');
            if (imports && imports.length > 0) {
                importsContainer.innerHTML = imports.map(item => 
                    `<div class="flex items-center justify-between text-xs">
                        <span class="text-gray-700">${item.name} (${item.employee_id})</span>
                        <span class="text-gray-500">${item.imported_at}</span>
                    </div>`
                ).join('');
            } else {
                importsContainer.innerHTML = '<div class="text-xs text-gray-500">No recent imports available</div>';
            }
        }

        // Quick Assessment Form Handler
        document.getElementById('quickAssessmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Validate form
            if (!formData.get('employee_id') || !formData.get('competency_id') || !formData.get('proficiency_level')) {
                Swal.fire({
                    title: 'Missing Information',
                    text: 'Please fill in all fields before submitting.',
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-1"></i> Adding...';
            
            // Make AJAX request to create assessment
            fetch('/competency/assessments/quick-create', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Assessment Added!',
                        text: 'Competency assessment has been successfully created.',
                        icon: 'success',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        // Reset form and refresh page
                        this.reset();
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to create assessment');
                }
            })
            .catch(error => {
                console.error('Assessment creation error:', error);
                Swal.fire({
                    title: 'Creation Failed',
                    text: 'Unable to create assessment. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });

        // Auto-refresh every 5 minutes (optional - can be removed if not needed)
        // setInterval(() => {
        //     refreshData();
        // }, 300000); // 5 minutes = 300,000ms
    </script>
</x-app-layout>