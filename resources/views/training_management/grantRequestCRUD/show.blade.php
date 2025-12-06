@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
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
                    <li>
                        <div class="flex items-center">
                            <i class='bx bx-chevron-right text-gray-400'></i>
                            <a href="{{ route('training.grant-request.index') }}" class="ml-1 text-gray-500 hover:text-gray-700 md:ml-2">Grant Request</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class='bx bx-chevron-right text-gray-400'></i>
                            <span class="ml-1 font-medium text-gray-900 md:ml-2">Request Details</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Title and Back Button -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class='bx bx-clipboard text-2xl text-blue-600 mr-3'></i>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Course Request Details</h1>
                        <p class="text-gray-600">Review and manage course request</p>
                    </div>
                </div>
                <a href="{{ route('training.grant-request.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Request Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-info-circle text-blue-600 mr-2'></i>
                        Request Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employee Details</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-lg font-semibold text-gray-900">{{ $request->employee_name }}</div>
                                <div class="text-gray-600">{{ $request->employee_email }}</div>
                                <div class="text-sm text-gray-500">Employee ID: {{ $request->employee_id }}</div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Request Status</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                @if($request->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class='bx bx-time-five mr-2'></i>
                                        Pending Review
                                    </span>
                                @elseif($request->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class='bx bx-check mr-2'></i>
                                        Approved
                                    </span>
                                @elseif($request->status === 'denied')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class='bx bx-x mr-2'></i>
                                        Denied
                                    </span>
                                @endif
                                
                                <div class="mt-2 text-sm text-gray-500">
                                    <div>Requested: {{ \Carbon\Carbon::parse($request->requested_at)->format('M d, Y g:i A') }}</div>
                                    @if($request->reviewed_at)
                                    <div>Reviewed: {{ \Carbon\Carbon::parse($request->reviewed_at)->format('M d, Y g:i A') }}</div>
                                    <div>Reviewed by: {{ $request->reviewed_by }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-book text-blue-600 mr-2'></i>
                        Requested Course
                    </h2>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $request->course_name }}</h3>
                                @if($request->course_description)
                                <p class="text-gray-600 mb-4">{{ $request->course_description }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($request->estimated_duration)
                            <div class="text-center bg-white rounded-lg p-3">
                                <i class='bx bx-time text-2xl text-blue-600 mb-2'></i>
                                <div class="text-sm text-gray-600">Duration</div>
                                <div class="font-semibold text-gray-900">{{ $request->estimated_duration }}</div>
                            </div>
                            @endif
                            
                            @if($request->course_category)
                            <div class="text-center bg-white rounded-lg p-3">
                                <i class='bx bx-category text-2xl text-blue-600 mb-2'></i>
                                <div class="text-sm text-gray-600">Category</div>
                                <div class="font-semibold text-gray-900">{{ $request->course_category }}</div>
                            </div>
                            @endif
                            
                            @if($request->difficulty_level)
                            <div class="text-center bg-white rounded-lg p-3">
                                <i class='bx bx-trending-up text-2xl text-blue-600 mb-2'></i>
                                <div class="text-sm text-gray-600">Level</div>
                                <div class="font-semibold text-gray-900">{{ ucfirst($request->difficulty_level) }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Training Materials -->
                @if($trainingMaterials->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-library text-blue-600 mr-2'></i>
                        Course Materials ({{ $trainingMaterials->count() }} lessons)
                    </h2>
                    
                    <div class="space-y-3">
                        @foreach($trainingMaterials as $material)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 mb-1">{{ $material->lesson_title }}</h4>
                                    @if($material->description)
                                    <p class="text-sm text-gray-600 mb-2">{{ $material->description }}</p>
                                    @endif
                                    @if($material->estimated_duration)
                                    <div class="text-xs text-gray-500">
                                        <i class='bx bx-time mr-1'></i>
                                        {{ $material->estimated_duration }}
                                    </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Published
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Justification -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-message-square-detail text-blue-600 mr-2'></i>
                        Employee Justification
                    </h2>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-800 whitespace-pre-line">{{ $request->justification }}</p>
                    </div>
                </div>

                <!-- Admin Notes -->
                @if($request->admin_notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-note text-blue-600 mr-2'></i>
                        Admin Notes
                    </h2>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-gray-800 whitespace-pre-line">{{ $request->admin_notes }}</p>
                        @if($request->reviewed_by)
                        <div class="mt-3 pt-3 border-t border-blue-200 text-sm text-blue-700">
                            <i class='bx bx-user mr-1'></i>
                            Added by {{ $request->reviewed_by }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                
                <!-- Action Buttons -->
                @if($request->status === 'pending')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    
                    <div class="space-y-3">
                        <button type="button" 
                                id="approve-request" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <i class='bx bx-check mr-2'></i>
                            Approve Request
                        </button>
                        
                        <button type="button" 
                                id="deny-request" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <i class='bx bx-x mr-2'></i>
                            Deny Request
                        </button>
                    </div>
                </div>
                @endif

                <!-- Request Summary -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Summary</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Request ID</span>
                            <span class="font-medium text-gray-900">#{{ $request->id }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Employee</span>
                            <span class="font-medium text-gray-900">{{ $request->employee_name }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Course</span>
                            <span class="font-medium text-gray-900 text-right text-sm">{{ $request->course_name }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Status</span>
                            <span class="font-medium">
                                @if($request->status === 'pending')
                                    <span class="text-yellow-600">Pending</span>
                                @elseif($request->status === 'approved')
                                    <span class="text-green-600">Approved</span>
                                @elseif($request->status === 'denied')
                                    <span class="text-red-600">Denied</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-600">Requested</span>
                            <span class="font-medium text-gray-900 text-sm">{{ \Carbon\Carbon::parse($request->requested_at)->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
                    
                    <div class="space-y-2">
                        <a href="{{ route('training.grant-request.index') }}" class="block w-full text-left text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg transition-colors duration-200">
                            <i class='bx bx-list-ul mr-2'></i>
                            All Requests
                        </a>
                        
                        <a href="{{ route('training.grant-request.index', ['status' => 'pending']) }}" class="block w-full text-left text-gray-700 hover:text-yellow-600 hover:bg-yellow-50 px-3 py-2 rounded-lg transition-colors duration-200">
                            <i class='bx bx-time-five mr-2'></i>
                            Pending Requests
                        </a>
                        
                        <a href="{{ route('training.catalog.index') }}" class="block w-full text-left text-gray-700 hover:text-green-600 hover:bg-green-50 px-3 py-2 rounded-lg transition-colors duration-200">
                            <i class='bx bx-book mr-2'></i>
                            Training Catalog
                        </a>
                        
                        <a href="{{ route('training.assign.index') }}" class="block w-full text-left text-gray-700 hover:text-purple-600 hover:bg-purple-50 px-3 py-2 rounded-lg transition-colors duration-200">
                            <i class='bx bx-user-check mr-2'></i>
                            Training Assignments
                        </a>
                    </div>
                </div>
            </div>
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
                <p class="text-sm text-gray-600 mb-4">
                    This will approve the course request and automatically create a training assignment for 
                    <strong>{{ $request->employee_name }}</strong> for the course 
                    <strong>{{ $request->course_name }}</strong>.
                </p>
                
                <label for="approve-notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                <textarea id="approve-notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Add any notes or comments about this approval..."></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <button type="button" id="cancelApprove" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="button" id="confirmApprove" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class='bx bx-check mr-1'></i>
                    Approve & Assign
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
                <p class="text-sm text-gray-600 mb-4">
                    Please provide a clear reason for denying this course request. This message will be visible to the employee.
                </p>
                
                <label for="deny-notes" class="block text-sm font-medium text-gray-700 mb-2">Reason for Denial <span class="text-red-500">*</span></label>
                <textarea id="deny-notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Please provide a reason for denying this request..." required></textarea>
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

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveModal = document.getElementById('approveModal');
    const denyModal = document.getElementById('denyModal');
    const requestId = {{ $request->id }};
    
    // Approve button
    document.getElementById('approve-request').addEventListener('click', function() {
        document.getElementById('approve-notes').value = '';
        approveModal.classList.remove('hidden');
    });
    
    // Deny button
    document.getElementById('deny-request').addEventListener('click', function() {
        document.getElementById('deny-notes').value = '';
        denyModal.classList.remove('hidden');
    });
    
    // Modal close buttons
    document.getElementById('closeApproveModal').addEventListener('click', () => approveModal.classList.add('hidden'));
    document.getElementById('cancelApprove').addEventListener('click', () => approveModal.classList.add('hidden'));
    
    document.getElementById('closeDenyModal').addEventListener('click', () => denyModal.classList.add('hidden'));
    document.getElementById('cancelDeny').addEventListener('click', () => denyModal.classList.add('hidden'));
    
    // Close modals on outside click
    [approveModal, denyModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
    
    // Confirm approve
    document.getElementById('confirmApprove').addEventListener('click', function() {
        const notes = document.getElementById('approve-notes').value.trim();
        
        fetch(`{{ route('training.grant-request.approve', '') }}/${requestId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                admin_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    window.location.href = '{{ route("training.grant-request.index") }}';
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An unexpected error occurred.',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        });
        
        approveModal.classList.add('hidden');
    });
    
    // Confirm deny
    document.getElementById('confirmDeny').addEventListener('click', function() {
        const notes = document.getElementById('deny-notes').value.trim();
        
        if (!notes) {
            Swal.fire({
                title: 'Validation Error',
                text: 'Please provide a reason for denying this request.',
                icon: 'warning',
                confirmButtonColor: '#3085d6'
            });
            return;
        }
        
        fetch(`{{ route('training.grant-request.deny', '') }}/${requestId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                admin_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Request Denied',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    window.location.href = '{{ route("training.grant-request.index") }}';
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An unexpected error occurred.',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        });
        
        denyModal.classList.add('hidden');
    });
});
</script>
@endpush