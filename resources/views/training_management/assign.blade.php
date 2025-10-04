<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <i class='bx bx-check-circle mr-2'></i>
                        </div>
                        <div>
                            <p class="font-bold">Success!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Assignments</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $assignments->total() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-task text-blue-600 text-2xl'></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $assignments->where('status', 'active')->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-time text-yellow-600 text-2xl'></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Completed</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $assignments->where('status', 'completed')->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Draft</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $assignments->where('status', 'draft')->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-clipboard text-red-600 text-2xl'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white shadow rounded-lg">
                <!-- Action Bar -->
                <div class="border-b border-gray-200 p-6">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <h2 class="text-lg font-semibold text-gray-900">Training Assignments</h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Coming Soon
                            </span>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('training.assign.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                <i class='bx bx-plus mr-2'></i>
                                New Assignment
                            </a>
                            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                <i class='bx bx-download mr-2'></i>
                                Export
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-gray-50 border-b border-gray-200 p-4">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Status:</label>
                            <select class="rounded-md border-gray-300 text-sm">
                                <option>All Status</option>
                                <option>Assigned</option>
                                <option>In Progress</option>
                                <option>Completed</option>
                                <option>Overdue</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Department:</label>
                            <select class="rounded-md border-gray-300 text-sm">
                                <option>All Departments</option>
                                <option>HR</option>
                                <option>IT</option>
                                <option>Finance</option>
                                <option>Operations</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Search:</label>
                            <input type="text" placeholder="Search assignments..." class="rounded-md border-gray-300 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="p-6">
                    @if($assignments && $assignments->count() > 0)
                        <!-- Assignments Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Employee</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Training Materials</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($assignments as $assignment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $assignment->assignment_title }}</div>
                                                    <div class="text-sm text-gray-500">{{ $assignment->assignment_type }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    @if($assignment->assignmentEmployees->count() > 0)
                                                        @foreach($assignment->assignmentEmployees as $assignmentEmployee)
                                                            @php
                                                                $employee = isset($employeesData) ? ($employeesData[$assignmentEmployee->employee_id] ?? null) : null;
                                                            @endphp
                                                            <div class="mb-1">
                                                                @if($employee)
                                                                    {{ $employee['full_name'] }}
                                                                    <span class="text-xs text-gray-500">({{ $employee['employee_id'] }} - {{ $employee['job_title'] }})</span>
                                                                    <div class="text-xs text-gray-400">
                                                                        {{ $employee['email'] }} • 
                                                                        <span class="inline-flex px-1 py-0.5 rounded text-xs {{ $employee['employment_status'] === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                                            {{ $employee['employment_status'] }}
                                                                        </span>
                                                                    </div>
                                                                @else
                                                                    <span class="text-red-500">Employee not found (ID: {{ $assignmentEmployee->employee_id }})</span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-gray-500">No employees assigned</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    @if($assignment->trainingMaterials->count() > 0)
                                                        @foreach($assignment->trainingMaterials as $material)
                                                            <div class="mb-1">
                                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                                                    {{ $material->lesson_title }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-gray-500">No materials assigned</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $assignment->priority_badge }}">
                                                    {{ ucfirst($assignment->priority) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $assignment->status_badge }}">
                                                    {{ ucfirst($assignment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $assignment->due_date->format('M d, Y') }}
                                                @if($assignment->is_overdue)
                                                    <span class="text-red-600 text-xs">(Overdue)</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('training.assign.show', $assignment) }}" class="text-blue-600 hover:text-blue-900">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    <a href="{{ route('training.assign.edit', $assignment) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    @if($assignment->status === 'draft')
                                                        <form method="POST" action="{{ route('training.assign.activate', $assignment) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Activate">
                                                                <i class='bx bx-play-circle'></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if(in_array($assignment->status, ['draft', 'active']))
                                                        <form method="POST" action="{{ route('training.assign.destroy', $assignment) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                                <i class='bx bx-trash'></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($assignments->hasPages())
                            <div class="mt-6">
                                {{ $assignments->links() }}
                            </div>
                        @endif
                        
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class='bx bx-user-plus text-gray-400 text-4xl'></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Training Assignments Yet</h3>
                            <p class="text-gray-500 mb-6">Get started by assigning training programs to your employees.</p>
                            <a href="{{ route('training.assign.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md inline-flex items-center">
                                <i class='bx bx-plus mr-2'></i>
                                Create First Assignment
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="border-b border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                </div>
                <div class="p-6">
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-history text-gray-400 text-2xl'></i>
                        </div>
                        <p class="text-gray-500">No recent activity to display</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .prose {
            max-width: none;
        }
        
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }
        
        .prose h1, .prose h2, .prose h3 {
            color: #1f2937;
            font-weight: 600;
        }
        
        .prose ul, .prose ol {
            padding-left: 1.5rem;
        }
        
        .prose li {
            margin: 0.5rem 0;
        }
    </style>
</x-app-layout>