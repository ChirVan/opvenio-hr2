<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex-1">
                    <!-- Breadcrumb -->
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                                    <i class='bx bx-home-alt mr-2.5'></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class='bx bx-chevron-right text-gray-400'></i>
                                    <span class="ml-1 md:ml-2 text-sm font-medium text-gray-500">Learning Management</span>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <i class='bx bx-chevron-right text-gray-400'></i>
                                    <span class="ml-1 md:ml-2 text-sm font-medium text-gray-900">Employee Assessments</span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <!-- Title and Description -->
                    <div class="flex items-center mb-2">
                        <i class='bx bx-user-check text-2xl text-blue-600 mr-3'></i>
                        <h1 class="text-2xl font-bold text-gray-900">Approved Employee Assessments</h1>
                    </div>
                    <p class="text-gray-600">View and track approved employee course requests and their training progress</p>
                </div>

                <!-- Stats Cards -->
                <div class="mt-6 lg:mt-0">
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <div class="flex items-center">
                                <i class='bx bx-check-circle text-green-600 text-2xl mr-3'></i>
                                <div>
                                    <p class="text-sm font-medium text-green-600">Approved Employees</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $statusCounts['approved'] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <i class='bx bx-book-open text-blue-600 text-2xl mr-3'></i>
                                <div>
                                    <p class="text-sm font-medium text-blue-600">Total Requests</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ array_sum($statusCounts) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                            <div class="flex items-center">
                                <i class='bx bx-trending-up text-purple-600 text-2xl mr-3'></i>
                                <div>
                                    <p class="text-sm font-medium text-purple-600">In Progress</p>
                                    <p class="text-2xl font-bold text-purple-900">{{ count($assignments) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                            <div class="flex items-center">
                                <i class='bx bx-task text-orange-600 text-2xl mr-3'></i>
                                <div>
                                    <p class="text-sm font-medium text-orange-600">Assigned Assessments</p>
                                    <p class="text-2xl font-bold text-orange-900">{{ $assessmentAssignments->flatten()->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <form method="GET" action="{{ route('learning.self-assess') }}" class="flex-1 space-y-4 lg:space-y-0 lg:flex lg:items-center lg:space-x-4">
                    <!-- Status Filter Tabs -->
                    <div class="flex flex-wrap gap-2 mb-4 lg:mb-0">
                        <a href="{{ route('learning.self-assess') }}" 
                           class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 {{ request('status') === null ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            All ({{ array_sum($statusCounts) }})
                        </a>
                        <a href="{{ route('learning.self-assess', ['status' => 'pending']) }}" 
                           class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 {{ request('status') === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Pending ({{ $statusCounts['pending'] ?? 0 }})
                        </a>
                        <a href="{{ route('learning.self-assess', ['status' => 'approved']) }}" 
                           class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 {{ request('status') === 'approved' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Approved ({{ $statusCounts['approved'] ?? 0 }})
                        </a>
                        <a href="{{ route('learning.self-assess', ['status' => 'denied']) }}" 
                           class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 {{ request('status') === 'denied' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Denied ({{ $statusCounts['denied'] ?? 0 }})
                        </a>
                    </div>

                    <!-- Search -->
                    <div class="flex-1 lg:max-w-md">
                        <div class="relative">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search courses or justifications..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Search Button -->
                    <button type="submit" class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class='bx bx-search mr-2'></i>
                        Search
                    </button>

                    <!-- Clear Filters -->
                    @if(request('search') || request('status'))
                    <a href="{{ route('learning.self-assess') }}" class="w-full lg:w-auto bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 text-center">
                        <i class='bx bx-x mr-2'></i>
                        Clear
                    </a>
                    @endif
                </form>

                <!-- Summary Info -->
                <div class="mt-4 lg:mt-0 lg:ml-4">
                    <div class="text-sm text-gray-600">
                        <i class='bx bx-info-circle mr-1'></i>
                        Showing approved employee assessments
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Requests Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($requests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $request->employee_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->employee_email }}</div>
                                        <div class="text-xs text-blue-600 mt-1">ID: {{ $request->employee_id }}</div>
                                        @php
                                            $employeeAssignments = $assessmentAssignments[$request->employee_id] ?? collect();
                                            $hasAssignments = $employeeAssignments->count() > 0;
                                            $pendingAssignments = $employeeAssignments->where('status', 'pending')->count();
                                            $completedAssignments = $employeeAssignments->where('status', 'completed')->count();
                                        @endphp
                                        @if($hasAssignments)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class='bx bx-check-square mr-1'></i>
                                                    {{ $employeeAssignments->count() }} Assessment{{ $employeeAssignments->count() > 1 ? 's' : '' }} Assigned
                                                </span>
                                                @if($pendingAssignments > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 ml-1">
                                                    {{ $pendingAssignments }} Pending
                                                </span>
                                                @endif
                                                @if($completedAssignments > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-1">
                                                    {{ $completedAssignments }} Completed
                                                </span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                    <i class='bx bx-info-circle mr-1'></i>
                                                    No Assessments Assigned
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $request->course_title }}</div>
                                        @if($request->course_description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($request->course_description, 60) }}</div>
                                        @endif
                                        @if($request->estimated_duration)
                                        <div class="text-xs text-blue-600 mt-1">Duration: {{ $request->estimated_duration }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($request->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class='bx bx-time mr-1'></i>
                                            Pending Review
                                        </span>
                                    @elseif($request->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class='bx bx-check mr-1'></i>
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class='bx bx-x mr-1'></i>
                                            Denied
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($request->requested_at)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php 
                                        $assignmentKey = $request->course_id . '_' . $request->employee_id;
                                        $assignment = $assignments[$assignmentKey] ?? null;
                                    @endphp
                                    @if($request->status === 'approved' && $assignment && $assignment->count() > 0)
                                        @php $assignmentData = $assignment->first() @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $assignmentData->progress_percentage }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">{{ number_format($assignmentData->progress_percentage, 1) }}% Complete</div>
                                        @if($assignmentData->progress_percentage >= 100)
                                        <div class="text-xs text-green-600 mt-1">
                                            <i class='bx bx-check mr-1'></i>Completed
                                        </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-500">{{ $request->status === 'approved' ? 'Training assigned - No progress yet' : 'Not applicable' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($request->admin_notes)
                                        <div class="text-sm text-gray-900">{{ Str::limit($request->admin_notes, 80) }}</div>
                                        @if($request->reviewed_at)
                                        <div class="text-xs text-gray-500 mt-1">Reviewed: {{ \Carbon\Carbon::parse($request->reviewed_at)->format('M d, Y') }}</div>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-500">No notes</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-col space-y-1">
                                        <button class="text-blue-600 hover:text-blue-900 view-details-btn" 
                                                data-id="{{ $request->id }}"
                                                data-course="{{ $request->course_title }}"
                                                data-description="{{ $request->course_description }}"
                                                data-justification="{{ $request->justification }}"
                                                data-status="{{ $request->status }}"
                                                data-notes="{{ $request->admin_notes }}"
                                                data-requested="{{ \Carbon\Carbon::parse($request->requested_at)->format('M d, Y g:i A') }}"
                                                data-reviewed="{{ $request->reviewed_at ? \Carbon\Carbon::parse($request->reviewed_at)->format('M d, Y g:i A') : '' }}">
                                            <i class='bx bx-show mr-1'></i>
                                            View Details
                                        </button>
                                        @php
                                            $employeeHasAssignments = isset($assessmentAssignments[$request->employee_id]) && $assessmentAssignments[$request->employee_id]->count() > 0;
                                        @endphp
                                        <a href="{{ route('learning.self-assess.create', ['employee_id' => $request->employee_id, 'course_id' => $request->course_id]) }}" 
                                           class="{{ $employeeHasAssignments ? 'text-blue-600 hover:text-blue-900' : 'text-green-600 hover:text-green-900' }} inline-flex items-center">
                                            <i class='bx {{ $employeeHasAssignments ? 'bx-plus' : 'bx-user-plus' }} mr-1'></i>
                                            {{ $employeeHasAssignments ? 'Add More' : 'Assign' }}
                                        </a>
                                    </div>
                                    @if($request->status === 'approved' && isset($assignments[$request->course_id]))
                                        <div class="mt-2">
                                            <a href="#" class="text-green-600 hover:text-green-900">
                                                <i class='bx bx-play mr-1'></i>
                                                Start Training
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-6 py-3 border-t border-gray-200">
                    {{ $requests->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <i class='bx bx-clipboard text-4xl text-gray-300 mb-4'></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Approved Assessments Found</h3>
                    <p class="text-gray-500 mb-4">
                        @if(request('search') || request('status'))
                            No approved employee assessments match your current filters.
                        @else
                            There are no approved employee course requests to display at this time.
                        @endif
                    </p>
                    @if(request('search') || request('status'))
                    <a href="{{ route('learning.self-assess') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Clear filters and view all assessments
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>



<!-- View Details Modal -->
<div id="viewDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-2/3 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Course Request Details</h3>
                <button type="button" id="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-xl'></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Course</label>
                    <p id="detail-course" class="text-sm text-gray-900 mt-1"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Course Description</label>
                    <p id="detail-description" class="text-sm text-gray-900 mt-1"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Your Justification</label>
                    <p id="detail-justification" class="text-sm text-gray-900 mt-1 bg-gray-50 p-3 rounded"></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p id="detail-status" class="text-sm mt-1"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Requested Date</label>
                        <p id="detail-requested" class="text-sm text-gray-900 mt-1"></p>
                    </div>
                </div>

                <div id="detail-admin-section" class="hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Administrator Notes</label>
                        <p id="detail-notes" class="text-sm text-gray-900 mt-1 bg-blue-50 p-3 rounded"></p>
                    </div>
                    <div id="detail-reviewed-section" class="mt-2 hidden">
                        <label class="block text-sm font-medium text-gray-700">Reviewed Date</label>
                        <p id="detail-reviewed" class="text-sm text-gray-900 mt-1"></p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-6">
                <button type="button" id="closeDetailsBtn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Modal elements
    const detailsModal = document.getElementById('viewDetailsModal');
    
    // View details functionality
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            
            document.getElementById('detail-course').textContent = data.course;
            document.getElementById('detail-description').textContent = data.description || 'No description available';
            document.getElementById('detail-justification').textContent = data.justification;
            document.getElementById('detail-requested').textContent = data.requested;
            
            // Status with styling
            const statusElement = document.getElementById('detail-status');
            if (data.status === 'pending') {
                statusElement.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="bx bx-time mr-1"></i>Pending Review</span>';
            } else if (data.status === 'approved') {
                statusElement.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="bx bx-check mr-1"></i>Approved</span>';
            } else {
                statusElement.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="bx bx-x mr-1"></i>Denied</span>';
            }
            
            // Admin notes section
            const adminSection = document.getElementById('detail-admin-section');
            const reviewedSection = document.getElementById('detail-reviewed-section');
            
            if (data.notes && data.notes.trim() !== '') {
                document.getElementById('detail-notes').textContent = data.notes;
                adminSection.classList.remove('hidden');
                
                if (data.reviewed && data.reviewed.trim() !== '') {
                    document.getElementById('detail-reviewed').textContent = data.reviewed;
                    reviewedSection.classList.remove('hidden');
                } else {
                    reviewedSection.classList.add('hidden');
                }
            } else {
                adminSection.classList.add('hidden');
            }
            
            detailsModal.classList.remove('hidden');
        });
    });
    
    // Modal close functionality
    document.getElementById('closeDetailsModal').addEventListener('click', () => detailsModal.classList.add('hidden'));
    document.getElementById('closeDetailsBtn').addEventListener('click', () => detailsModal.classList.add('hidden'));
    
    // Close modal on outside click
    detailsModal.addEventListener('click', function(e) {
        if (e.target === detailsModal) {
            detailsModal.classList.add('hidden');
        }
    });
});
</script>

</x-app-layout>
