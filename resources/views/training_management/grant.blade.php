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
                                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 inline-flex items-center">
                                    <i class='bx bx-home mr-2'></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class='bx bx-chevron-right text-gray-400'></i>
                                    <span class="ml-1 text-gray-500 md:ml-2">Training Management</span>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <i class='bx bx-chevron-right text-gray-400'></i>
                                    <span class="ml-1 font-medium text-gray-900 md:ml-2">Grant Request</span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <!-- Title and Description -->
                    <div class="flex items-center mb-2">
                        <i class='bx bx-clipboard text-2xl text-blue-600 mr-3'></i>
                        <h1 class="text-2xl font-bold text-gray-900">Course Grant Requests</h1>
                    </div>
                    <p class="text-gray-600">Manage and review course requests submitted by employees</p>
                </div>

                <!-- Stats Cards -->
                <div class="mt-6 lg:mt-0">
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center min-w-[100px]">
                            <div class="text-2xl font-bold text-blue-600" id="total-requests">{{ ($statusCounts['pending'] ?? 0) + ($statusCounts['approved'] ?? 0) + ($statusCounts['denied'] ?? 0) }}</div>
                            <div class="text-sm text-blue-700">Total</div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center min-w-[100px]">
                            <div class="text-2xl font-bold text-yellow-600" id="pending-requests">{{ $statusCounts['pending'] ?? 0 }}</div>
                            <div class="text-sm text-yellow-700">Pending</div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center min-w-[100px]">
                            <div class="text-2xl font-bold text-green-600" id="approved-requests">{{ $statusCounts['approved'] ?? 0 }}</div>
                            <div class="text-sm text-green-700">Approved</div>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center min-w-[100px]">
                            <div class="text-2xl font-bold text-red-600" id="denied-requests">{{ $statusCounts['denied'] ?? 0 }}</div>
                            <div class="text-sm text-red-700">Denied</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('training.grant-request.index') }}" class="space-y-4 lg:space-y-0 lg:flex lg:items-center lg:space-x-4">
                <!-- Status Filter Tabs -->
                <div class="flex flex-wrap gap-2 mb-4 lg:mb-0">
                    <a href="{{ route('training.grant-request.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ request('status') == '' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All ({{ array_sum($statusCounts) }})
                    </a>
                    <a href="{{ route('training.grant-request.index', ['status' => 'pending']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Pending ({{ $statusCounts['pending'] ?? 0 }})
                    </a>
                    <a href="{{ route('training.grant-request.index', ['status' => 'approved']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ request('status') == 'approved' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Approved ({{ $statusCounts['approved'] ?? 0 }})
                    </a>
                    <a href="{{ route('training.grant-request.index', ['status' => 'denied']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ request('status') == 'denied' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Denied ({{ $statusCounts['denied'] ?? 0 }})
                    </a>
                </div>

                <!-- Search -->
                <div class="flex-1 lg:max-w-md">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by employee name, email, or course..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                    </div>
                </div>

                <!-- Search Button -->
                <button type="submit" class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class='bx bx-search mr-2'></i>
                    Search
                </button>

                <!-- Clear Filters -->
                @if(request('search') || request('status'))
                <a href="{{ route('training.grant-request.index') }}" class="w-full lg:w-auto bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 text-center">
                    <i class='bx bx-x mr-2'></i>
                    Clear
                </a>
                @endif
            </form>

            <!-- Bulk Actions -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center space-x-4 mb-4 lg:mb-0">
                        <label class="text-sm font-medium text-gray-700">Bulk Actions:</label>
                        <button type="button" id="bulk-approve-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <i class='bx bx-check mr-1'></i>
                            Approve Selected
                        </button>
                        <button type="button" id="bulk-deny-btn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <i class='bx bx-x mr-1'></i>
                            Deny Selected
                        </button>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span id="selected-count">0</span> requests selected
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($requests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Justification</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="request-checkbox rounded border-black-300 text-blue-600 focus:ring-blue-500" 
                                           value="{{ $request->id }}" data-status="{{ $request->status }}">
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $request->employee_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->employee_email }}</div>
                                        <div class="text-xs text-gray-400">ID: {{ $request->employee_id }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $request->course_name }}</div>
                                        @if($request->estimated_duration)
                                        <div class="text-xs text-gray-500">Duration: {{ $request->estimated_duration }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $request->justification }}">
                                        {{ $request->justification }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($request->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class='bx bx-time-five mr-1'></i>
                                            Pending
                                        </span>
                                    @elseif($request->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class='bx bx-check mr-1'></i>
                                            Approved
                                        </span>
                                    @elseif($request->status === 'denied')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class='bx bx-x mr-1'></i>
                                            Denied
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($request->requested_at)->format('M d, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <!-- View Details -->
                                        <a href="{{ route('training.grant-request.show', $request->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                            <i class='bx bx-show mr-1'></i>
                                            View
                                        </a>

                                        @if($request->status === 'pending')
                                        <!-- Quick Approve -->
                                        <button type="button" 
                                                class="approve-btn text-green-600 hover:text-green-900 font-medium text-sm px-3 py-1 rounded border border-green-200 bg-green-50 hover:bg-green-100 transition-colors"
                                                data-id="{{ $request->id }}"
                                                data-employee="{{ $request->employee_name }}"
                                                data-course="{{ $request->course_name }}">
                                            <i class='bx bx-check mr-1'></i>
                                            Approve
                                        </button>

                                        <!-- Quick Deny -->
                                        <button type="button" 
                                                class="deny-btn text-red-600 hover:text-red-900 font-medium text-sm px-3 py-1 rounded border border-red-200 bg-red-50 hover:bg-red-100 transition-colors"
                                                data-id="{{ $request->id }}"
                                                data-employee="{{ $request->employee_name }}"
                                                data-course="{{ $request->course_name }}">
                                            <i class='bx bx-x mr-1'></i>
                                            Deny
                                        </button>
                                        @endif

                                        @if($request->status === 'approved')
                                        <!-- Assign Assessment -->
                                        <button type="button" 
                                                class="assign-assessment-btn text-purple-600 hover:text-purple-900 font-medium text-sm px-3 py-1 rounded border border-purple-200 bg-purple-50 hover:bg-purple-100 transition-colors"
                                                data-id="{{ $request->id }}"
                                                data-employee="{{ $request->employee_name }}"
                                                data-employee-id="{{ $request->employee_id }}"
                                                data-course="{{ $request->course_name }}"
                                                data-course-id="{{ $request->course_id }}">
                                            <i class='bx bx-clipboard mr-1'></i>
                                            Assign Assessment
                                        </button>
                                        @endif
                                    </div>
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Course Requests Found</h3>
                    <p class="text-gray-500 mb-4">
                        @if(request('search') || request('status'))
                            No requests match your current filters. Try adjusting your search criteria.
                        @else
                            No course requests have been submitted yet.
                        @endif
                    </p>
                    @if(request('search') || request('status'))
                    <a href="{{ route('training.grant-request.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Clear all filters
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Approve Course Request</h3>
                <button type="button" id="closeApproveModal" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-xl'></i>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Employee: <span id="approve-employee-name" class="font-medium"></span></p>
                <p class="text-sm text-gray-600 mb-4">Course: <span id="approve-course-name" class="font-medium"></span></p>
                
                <label for="approve-notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                <textarea id="approve-notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Add any notes or comments about this approval..."></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <button type="button" id="cancelApprove" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="button" id="confirmApprove" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class='bx bx-check mr-1'></i>
                    Approve Request
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Deny Modal -->
<div id="denyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Deny Course Request</h3>
                <button type="button" id="closeDenyModal" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-xl'></i>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Employee: <span id="deny-employee-name" class="font-medium"></span></p>
                <p class="text-sm text-gray-600 mb-4">Course: <span id="deny-course-name" class="font-medium"></span></p>
                
                <label for="deny-notes" class="block text-sm font-medium text-gray-700 mb-2">Reason for Denial <span class="text-red-500">*</span></label>
                <textarea id="deny-notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Please provide a reason for denying this request..." required></textarea>
                <p class="text-xs text-gray-500 mt-1">This reason will be visible to the employee.</p>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <button type="button" id="cancelDeny" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="button" id="confirmDeny" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <i class='bx bx-x mr-1'></i>
                    Deny Request
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div id="bulkActionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900" id="bulk-action-title">Bulk Action</h3>
                <button type="button" id="closeBulkModal" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-xl'></i>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-4" id="bulk-action-message"></p>
                
                <label for="bulk-notes" class="block text-sm font-medium text-gray-700 mb-2" id="bulk-notes-label">Notes</label>
                <textarea id="bulk-notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Add notes for all selected requests..."></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <button type="button" id="cancelBulkAction" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="button" id="confirmBulkAction" class="px-4 py-2 rounded-md text-white focus:outline-none focus:ring-2">
                    <i class='bx mr-1' id="bulk-action-icon"></i>
                    <span id="bulk-action-button-text">Confirm</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Assign Assessment Modal -->
<div id="assignAssessmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-[500px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class='bx bx-clipboard text-purple-600 mr-2'></i>
                    Assign Assessment
                </h3>
                <button type="button" id="closeAssessmentModal" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-xl'></i>
                </button>
            </div>
            
            <div class="mb-4">
                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                    <p class="text-sm text-gray-600 mb-1">Employee: <span id="assessment-employee-name" class="font-medium text-gray-900"></span></p>
                    <p class="text-sm text-gray-600">Course: <span id="assessment-course-name" class="font-medium text-gray-900"></span></p>
                </div>
                
                <!-- Loading State -->
                <div id="assessment-loading" class="text-center py-4">
                    <i class='bx bx-loader-alt bx-spin text-2xl text-purple-600'></i>
                    <p class="text-sm text-gray-500 mt-2">Loading related assessments...</p>
                </div>
                
                <!-- No Assessments Found -->
                <div id="assessment-empty" class="text-center py-4 hidden">
                    <i class='bx bx-info-circle text-3xl text-gray-400'></i>
                    <p class="text-sm text-gray-500 mt-2">No related assessments found for this course.</p>
                </div>
                
                <!-- Assessment List -->
                <div id="assessment-list" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Assessment(s) to Assign:</label>
                    <div id="assessment-items" class="max-h-64 overflow-y-auto space-y-2 border border-gray-200 rounded-lg p-3">
                        <!-- Assessment items will be populated here -->
                    </div>
                    
                    <div class="mt-4">
                        <label for="assessment-due-date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                        <input type="date" id="assessment-due-date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    
                    <div class="mt-4">
                        <label for="assessment-notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="assessment-notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Add any notes about this assessment assignment..."></textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <button type="button" id="cancelAssessment" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="button" id="confirmAssessment" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <i class='bx bx-check mr-1'></i>
                    Assign Assessment
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Current request data
    let currentRequestId = null;
    let currentAction = null;
    let currentEmployeeId = null;
    let currentCourseId = null;
    
    // Modal elements
    const approveModal = document.getElementById('approveModal');
    const denyModal = document.getElementById('denyModal');
    const bulkActionModal = document.getElementById('bulkActionModal');
    const assignAssessmentModal = document.getElementById('assignAssessmentModal');
    
    // Use event delegation for approve/deny/assign assessment buttons
    document.addEventListener('click', function(e) {
        // Handle Approve button clicks
        if (e.target.closest('.approve-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const btn = e.target.closest('.approve-btn');
            currentRequestId = btn.dataset.id;
            document.getElementById('approve-employee-name').textContent = btn.dataset.employee;
            document.getElementById('approve-course-name').textContent = btn.dataset.course;
            document.getElementById('approve-notes').value = '';
            approveModal.classList.remove('hidden');
            return;
        }
        
        // Handle Deny button clicks
        if (e.target.closest('.deny-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const btn = e.target.closest('.deny-btn');
            currentRequestId = btn.dataset.id;
            document.getElementById('deny-employee-name').textContent = btn.dataset.employee;
            document.getElementById('deny-course-name').textContent = btn.dataset.course;
            document.getElementById('deny-notes').value = '';
            denyModal.classList.remove('hidden');
            return;
        }
        
        // Handle Assign Assessment button clicks
        if (e.target.closest('.assign-assessment-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const btn = e.target.closest('.assign-assessment-btn');
            currentRequestId = btn.dataset.id;
            currentEmployeeId = btn.dataset.employeeId;
            currentCourseId = btn.dataset.courseId;
            
            document.getElementById('assessment-employee-name').textContent = btn.dataset.employee;
            document.getElementById('assessment-course-name').textContent = btn.dataset.course;
            
            // Reset modal state
            document.getElementById('assessment-loading').classList.remove('hidden');
            document.getElementById('assessment-empty').classList.add('hidden');
            document.getElementById('assessment-empty').innerHTML = `
                <i class='bx bx-info-circle text-3xl text-gray-400'></i>
                <p class="text-sm text-gray-500 mt-2">No related assessments found for this course.</p>
            `;
            document.getElementById('assessment-list').classList.add('hidden');
            document.getElementById('confirmAssessment').disabled = true;
            document.getElementById('assessment-notes').value = '';
            
            // Set default due date (30 days from now)
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + 30);
            document.getElementById('assessment-due-date').value = dueDate.toISOString().split('T')[0];
            
            assignAssessmentModal.classList.remove('hidden');
            
            // Fetch related assessments with employee ID
            fetchRelatedAssessments(currentCourseId, currentEmployeeId);
            return;
        }
    });
    
    // Checkbox management
    const selectAllCheckbox = document.getElementById('select-all');
    const requestCheckboxes = document.querySelectorAll('.request-checkbox');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkApproveBtn = document.getElementById('bulk-approve-btn');
    const bulkDenyBtn = document.getElementById('bulk-deny-btn');
    
    // Update selected count and bulk button states
    function updateBulkActions() {
        const selectedCheckboxes = document.querySelectorAll('.request-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;
        const pendingSelected = Array.from(selectedCheckboxes).filter(cb => cb.dataset.status === 'pending').length;
        
        selectedCountSpan.textContent = selectedCount;
        
        // Enable/disable bulk buttons based on pending requests
        bulkApproveBtn.disabled = pendingSelected === 0;
        bulkDenyBtn.disabled = pendingSelected === 0;
    }
    
    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            requestCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkActions();
        });
    }
    
    // Individual checkbox change
    requestCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            updateBulkActions();
            
            // Update select all checkbox
            if (selectAllCheckbox) {
                const allChecked = Array.from(requestCheckboxes).every(checkbox => checkbox.checked);
                const someChecked = Array.from(requestCheckboxes).some(checkbox => checkbox.checked);
                
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
        });
    });
    
    // Bulk action buttons
    bulkApproveBtn.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.request-checkbox:checked'))
            .filter(cb => cb.dataset.status === 'pending')
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) return;
        
        currentAction = 'approve';
        document.getElementById('bulk-action-title').textContent = 'Approve Requests';
        document.getElementById('bulk-action-message').textContent = `Are you sure you want to approve ${selectedIds.length} course request(s)?`;
        document.getElementById('bulk-notes-label').textContent = 'Admin Notes (Optional)';
        document.getElementById('bulk-notes').placeholder = 'Add notes for all approved requests...';
        document.getElementById('bulk-notes').required = false;
        
        const confirmBtn = document.getElementById('confirmBulkAction');
        confirmBtn.className = 'px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500';
        document.getElementById('bulk-action-icon').className = 'bx bx-check mr-1';
        document.getElementById('bulk-action-button-text').textContent = 'Approve All';
        
        bulkActionModal.classList.remove('hidden');
    });
    
    bulkDenyBtn.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.request-checkbox:checked'))
            .filter(cb => cb.dataset.status === 'pending')
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) return;
        
        currentAction = 'deny';
        document.getElementById('bulk-action-title').textContent = 'Deny Requests';
        document.getElementById('bulk-action-message').textContent = `Are you sure you want to deny ${selectedIds.length} course request(s)?`;
        document.getElementById('bulk-notes-label').innerHTML = 'Reason for Denial <span class="text-red-500">*</span>';
        document.getElementById('bulk-notes').placeholder = 'Please provide a reason for denying these requests...';
        document.getElementById('bulk-notes').required = true;
        
        const confirmBtn = document.getElementById('confirmBulkAction');
        confirmBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500';
        document.getElementById('bulk-action-icon').className = 'bx bx-x mr-1';
        document.getElementById('bulk-action-button-text').textContent = 'Deny All';
        
        bulkActionModal.classList.remove('hidden');
    });
    
    // Modal close buttons
    document.getElementById('closeApproveModal').addEventListener('click', () => approveModal.classList.add('hidden'));
    document.getElementById('cancelApprove').addEventListener('click', () => approveModal.classList.add('hidden'));
    
    document.getElementById('closeDenyModal').addEventListener('click', () => denyModal.classList.add('hidden'));
    document.getElementById('cancelDeny').addEventListener('click', () => denyModal.classList.add('hidden'));
    
    document.getElementById('closeBulkModal').addEventListener('click', () => bulkActionModal.classList.add('hidden'));
    document.getElementById('cancelBulkAction').addEventListener('click', () => bulkActionModal.classList.add('hidden'));
    
    // Close modals on outside click
    [approveModal, denyModal, bulkActionModal, assignAssessmentModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
    
    // Assessment Modal close buttons
    document.getElementById('closeAssessmentModal').addEventListener('click', () => assignAssessmentModal.classList.add('hidden'));
    document.getElementById('cancelAssessment').addEventListener('click', () => assignAssessmentModal.classList.add('hidden'));
    
    // Fetch related assessments for the course
    function fetchRelatedAssessments(courseId, employeeId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        fetch(`/training/grant-request/get-related-assessments/${courseId}/${employeeId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('assessment-loading').classList.add('hidden');
            
            if (data.success && data.assessments && data.assessments.length > 0) {
                // Check if all assessments are already assigned
                if (data.all_assigned) {
                    document.getElementById('assessment-empty').classList.remove('hidden');
                    document.getElementById('assessment-empty').innerHTML = `
                        <i class='bx bx-check-circle text-3xl text-green-500'></i>
                        <p class="text-sm text-green-600 mt-2 font-medium">All related assessments have already been assigned to this employee.</p>
                    `;
                } else {
                    document.getElementById('assessment-list').classList.remove('hidden');
                    renderAssessmentItems(data.assessments, data.assigned_quiz_ids || []);
                }
            } else {
                document.getElementById('assessment-empty').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('assessment-loading').classList.add('hidden');
            document.getElementById('assessment-empty').classList.remove('hidden');
        });
    }
    
    // Render assessment items in the modal
    function renderAssessmentItems(assessments, assignedQuizIds) {
        const container = document.getElementById('assessment-items');
        container.innerHTML = '';
        
        let hasUnassigned = false;
        
        assessments.forEach(assessment => {
            const isAssigned = assignedQuizIds.includes(assessment.id);
            if (!isAssigned) hasUnassigned = true;
            
            const item = document.createElement('div');
            item.className = `flex items-start p-3 rounded-lg transition-colors ${isAssigned ? 'bg-green-50 opacity-75' : 'bg-gray-50 hover:bg-purple-50'}`;
            item.innerHTML = `
                <input type="checkbox" class="assessment-checkbox mt-1 rounded border-black-300 text-purple-600 focus:ring-purple-500 ${isAssigned ? 'cursor-not-allowed' : ''}" 
                       value="${assessment.id}" data-title="${assessment.quiz_title}" ${isAssigned ? 'disabled checked' : ''}>
                <div class="ml-3 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium ${isAssigned ? 'text-green-700' : 'text-gray-900'}">${assessment.quiz_title}</span>
                        ${isAssigned ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"><i class="bx bx-check mr-1"></i>Assigned</span>' : ''}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        <span class="inline-flex items-center">
                            <i class='bx bx-time-five mr-1'></i>${assessment.time_limit} mins
                        </span>
                        <span class="inline-flex items-center ml-3">
                            <i class='bx bx-question-mark mr-1'></i>${assessment.total_questions} questions
                        </span>
                        <span class="inline-flex items-center ml-3">
                            <i class='bx bx-star mr-1'></i>${assessment.total_points} points
                        </span>
                    </div>
                    <div class="text-xs text-gray-400 mt-1">${assessment.description || ''}</div>
                </div>
            `;
            container.appendChild(item);
        });
        
        // Add change event listeners for checkboxes (only non-disabled ones)
        document.querySelectorAll('.assessment-checkbox:not(:disabled)').forEach(cb => {
            cb.addEventListener('change', updateConfirmAssessmentButton);
        });
    }
    
    // Update confirm button state based on checkbox selection (only non-disabled checkboxes)
    function updateConfirmAssessmentButton() {
        const selectedCheckboxes = document.querySelectorAll('.assessment-checkbox:checked:not(:disabled)');
        document.getElementById('confirmAssessment').disabled = selectedCheckboxes.length === 0;
    }
    
    // Confirm assessment assignment
    document.getElementById('confirmAssessment').addEventListener('click', function() {
        const selectedAssessments = Array.from(document.querySelectorAll('.assessment-checkbox:checked:not(:disabled)')).map(cb => ({
            id: cb.value,
            title: cb.dataset.title
        }));
        
        if (selectedAssessments.length === 0) {
    swalNotify('error', 'Validation Error', 'Please select at least one assessment to assign.');
    return;
}
        
        const dueDate = document.getElementById('assessment-due-date').value;
        const notes = document.getElementById('assessment-notes').value.trim();
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        // Disable button and show loading
        const confirmBtn = document.getElementById('confirmAssessment');
        const originalText = confirmBtn.innerHTML;
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-1"></i> Assigning...';
        
        fetch('/training/grant-request/assign-assessment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                request_id: currentRequestId,
                employee_id: currentEmployeeId,
                assessment_ids: selectedAssessments.map(a => a.id),
                due_date: dueDate,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
    // Show success message
    swalNotify('success', 'Success', data.message || 'Assessment(s) assigned successfully!');
    assignAssessmentModal.classList.add('hidden');
    setTimeout(() => location.reload(), 800);
} else {
    swalNotify('error', 'Error', data.message || 'Failed to assign assessment.');
    confirmBtn.disabled = false;
    confirmBtn.innerHTML = originalText;
}
        })
        .catch(error => {
    console.error('Error:', error);
    swalNotify('error', 'Network Error', error.message || 'An unexpected error occurred.');
    confirmBtn.disabled = false;
    confirmBtn.innerHTML = originalText;
});
    });
    
    // Confirm approve
    document.getElementById('confirmApprove').addEventListener('click', function() {
        const notes = document.getElementById('approve-notes').value.trim();
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        fetch(`/training/grant-request/${currentRequestId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                admin_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: An unexpected error occurred.');
        });
        
        approveModal.classList.add('hidden');
    });
    
    // Confirm deny
    document.getElementById('confirmDeny').addEventListener('click', function() {
        const notes = document.getElementById('deny-notes').value.trim();
        
        if (!notes) {
            alert('Please provide a reason for denying this request.');
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        fetch(`/training/grant-request/${currentRequestId}/deny`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                admin_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: An unexpected error occurred.');
        });
        
        denyModal.classList.add('hidden');
    });
    
    // Confirm bulk action
    document.getElementById('confirmBulkAction').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.request-checkbox:checked'))
            .filter(cb => cb.dataset.status === 'pending')
            .map(cb => cb.value);
        
        const notes = document.getElementById('bulk-notes').value.trim();
        
        if (currentAction === 'deny' && !notes) {
            alert('Please provide a reason for denying these requests.');
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        fetch('/training/grant-request/bulk-action', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                action: currentAction,
                request_ids: selectedIds,
                admin_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: An unexpected error occurred.');
        });
        
        bulkActionModal.classList.add('hidden');
    });
    
    // Initialize bulk actions state
    updateBulkActions();
});
</script>

</x-app-layout>
