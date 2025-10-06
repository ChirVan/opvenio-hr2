<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Assessment Hub</h1>
                    <p class="text-gray-600 mt-1">Comprehensive assessment management and analytics dashboard</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('learning.assessment') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                        <i class='bx bx-category mr-2'></i>
                        Assessment Center
                    </a>
                    <a href="{{ route('learning.hub.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                        <i class='bx bx-plus mr-2'></i>
                        Quick Assessment
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total Assessments -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Assignments</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i class='bx bx-file-blank'></i> All assignments
                        </p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-full">
                        <i class='bx bx-file-blank text-2xl text-blue-600'></i>
                    </div>
                </div>
            </div>

            <!-- Pending Assessments -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                        <p class="text-sm text-yellow-600 mt-1">
                            <i class='bx bx-time'></i> Not started
                        </p>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-full">
                        <i class='bx bx-time text-2xl text-yellow-600'></i>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">In Progress</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['in_progress'] ?? 0 }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i class='bx bx-play-circle'></i> Currently active
                        </p>
                    </div>
                    <div class="bg-green-50 p-3 rounded-full">
                        <i class='bx bx-play text-2xl text-green-600'></i>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['completed'] ?? 0 }}</p>
                        <p class="text-sm text-purple-600 mt-1">
                            <i class='bx bx-check-circle'></i> Finished
                        </p>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-full">
                        <i class='bx bx-check text-2xl text-purple-600'></i>
                    </div>
                </div>
            </div>

            <!-- Overdue -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Overdue</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['overdue'] ?? 0 }}</p>
                        <p class="text-sm text-red-600 mt-1">
                            <i class='bx bx-error-circle'></i> Past deadline
                        </p>
                    </div>
                    <div class="bg-red-50 p-3 rounded-full">
                        <i class='bx bx-error-circle text-2xl text-red-600'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assessment Assignments Full Width -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="text-lg font-semibold text-gray-900">Assessment Assignments</h3>
                    <div class="flex items-center space-x-3">
                        <!-- Filter Dropdown -->
                        <select id="statusFilter" class="text-sm border border-gray-300 rounded-md px-3 py-1 bg-white">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                        <a href="{{ route('learning.hub.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Create New</a>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if(isset($assignments) && $assignments->count() > 0)
                    <div class="space-y-4">
                        @foreach($assignments as $assignment)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    @php
                                        $statusColors = [
                                            'completed' => 'green',
                                            'in_progress' => 'blue', 
                                            'overdue' => 'red',
                                            'pending' => 'yellow'
                                        ];
                                        $statusIcons = [
                                            'completed' => 'check',
                                            'in_progress' => 'play',
                                            'overdue' => 'error',
                                            'pending' => 'time'
                                        ];
                                        $color = $statusColors[$assignment->status] ?? 'yellow';
                                        $icon = $statusIcons[$assignment->status] ?? 'time';
                                    @endphp
                                    <div class="bg-{{ $color }}-100 p-2 rounded-full">
                                        <i class='bx bx-{{ $icon }} text-{{ $color }}-600'></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $assignment->quiz->quiz_title ?? 'Quiz Title' }}</h4>
                                        <p class="text-sm text-gray-600">{{ $assignment->assessmentCategory->category_name ?? 'Category' }} • Assigned to {{ $assignment->employee_name }}</p>
                                        <p class="text-xs text-gray-500">
                                            Created {{ $assignment->created_at->diffForHumans() }}
                                            @if($assignment->due_date)
                                                • Due {{ $assignment->due_date->format('M j, Y') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                        {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                    </span>
                                    @if($assignment->score !== null)
                                        <p class="text-sm text-gray-600 mt-1">Score: {{ $assignment->score }}%</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if(isset($assignments) && $assignments->hasPages())
                    <div class="mt-6 border-t pt-4">
                        {{ $assignments->links() }}
                    </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <i class='bx bx-clipboard text-4xl text-gray-400 mb-2'></i>
                        <p class="text-gray-600">No assessment assignments found</p>
                        <p class="text-sm text-gray-500 mt-1">Create your first assignment to get started</p>
                        <a href="{{ route('learning.hub.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md">
                            <i class='bx bx-plus mr-2'></i>
                            Create Assignment
                        </a>
                    </div>
                @endif
            </div>
        </div>


    </div>

    <style>
        /* Custom animations for the assessment hub */
        .assessment-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .assessment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Pulse animation for active indicators */
        .pulse-dot {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Smooth hover effects */
        .hover-lift:hover {
            transform: translateY(-1px);
            transition: transform 0.2s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth animations to statistics cards
            const statCards = document.querySelectorAll('.border-l-4');
            statCards.forEach(card => {
                card.classList.add('assessment-card');
            });
            
            // Add hover effects to quick action buttons
            const quickActions = document.querySelectorAll('.space-y-3 a');
            quickActions.forEach(button => {
                button.classList.add('hover-lift');
            });
            
            // Status filter functionality
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    const currentUrl = new URL(window.location);
                    if (this.value) {
                        currentUrl.searchParams.set('status', this.value);
                    } else {
                        currentUrl.searchParams.delete('status');
                    }
                    window.location.href = currentUrl.toString();
                });
            }
        });
    </script>
</x-app-layout>