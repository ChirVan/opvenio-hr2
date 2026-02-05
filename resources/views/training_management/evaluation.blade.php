<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6 px-4">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-t-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Training Evaluation</h1>
                    <p class="text-purple-100">Hands-on Performance Assessment for Employees Who Passed Step 1</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 bg-white/20 rounded-lg">
                        <i class='bx bx-clipboard text-xl mr-2'></i>
                        <span class="font-semibold">{{ $stats['total_pending'] }} Pending</span>
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg">
            <!-- Stats Cards -->
            <div class="p-6 border-b border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <i class='bx bx-time-five text-2xl text-white'></i>
                            </div>
                            <div>
                                <p class="text-sm text-yellow-700 font-medium">Pending Evaluation</p>
                                <p class="text-2xl font-bold text-yellow-800">{{ $stats['total_pending'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class='bx bx-check-circle text-2xl text-white'></i>
                            </div>
                            <div>
                                <p class="text-sm text-green-700 font-medium">Evaluated</p>
                                <p class="text-2xl font-bold text-green-800">{{ $stats['total_evaluated'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class='bx bx-user-check text-2xl text-white'></i>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700 font-medium">Total Employees</p>
                                <p class="text-2xl font-bold text-blue-800">{{ $stats['total_employees'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="p-6 border-b border-gray-100">
                <form method="GET" action="{{ route('training.evaluation.index') }}" class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by employee name..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div>
                        <select name="filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('filter') == 'pending' ? 'selected' : '' }}>Pending Evaluation</option>
                            <option value="evaluated" {{ request('filter') == 'evaluated' ? 'selected' : '' }}>Evaluated</option>
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition">
                        <i class='bx bx-search mr-1'></i> Search
                    </button>
                    <a href="{{ route('training.evaluation.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                        <i class='bx bx-reset mr-1'></i> Reset
                    </a>
                </form>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mx-6 mt-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <i class='bx bx-check-circle mr-2'></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-6 mt-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <i class='bx bx-error-circle mr-2'></i>{{ session('error') }}
                </div>
            @endif

            <!-- Employees Table -->
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Assessments</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Evaluation Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Last Completed</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($groupedResults as $employee)
                                <tr class="hover:bg-purple-50 transition">
                                    <!-- Employee Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-purple-400 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($employee->employee_name, 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $employee->employee_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $employee->employee_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Assessments -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $employee->total_assessments }} Assessment(s)</div>
                                        <div class="text-xs text-gray-500">
                                            @foreach($employee->assessments->take(2) as $assessment)
                                                <span class="inline-block bg-gray-100 px-2 py-0.5 rounded text-xs mr-1 mb-1">{{ Str::limit($assessment->quiz_title, 20) }}</span>
                                            @endforeach
                                            @if($employee->assessments->count() > 2)
                                                <span class="text-gray-400">+{{ $employee->assessments->count() - 2 }} more</span>
                                            @endif
                                        </div>
                                    </td>
                                    <!-- Progress -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-2">
                                                @php
                                                    $progress = $employee->total_assessments > 0 
                                                        ? ($employee->evaluated_count / $employee->total_assessments) * 100 
                                                        : 0;
                                                @endphp
                                                <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-600">{{ $employee->evaluated_count }}/{{ $employee->total_assessments }}</span>
                                        </div>
                                    </td>
                                    <!-- Last Completed -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if($employee->latest_completed)
                                            <i class='bx bx-calendar text-gray-400 mr-1'></i>
                                            {{ \Carbon\Carbon::parse($employee->latest_completed)->format('M d, Y') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($employee->all_evaluated)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                <i class='bx bx-check-circle mr-1'></i> Evaluated
                                            </span>
                                        @elseif($employee->evaluated_count > 0)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                                <i class='bx bx-loader-alt mr-1'></i> In Progress
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                                <i class='bx bx-time-five mr-1'></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Action -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(!$employee->all_evaluated)
                                            <a href="{{ route('training.evaluation.evaluate', $employee->employee_id) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg font-semibold text-xs shadow-md transition-all duration-200">
                                                <i class='bx bx-clipboard mr-1'></i> Evaluate
                                            </a>
                                        @else
                                            <a href="{{ route('training.evaluation.evaluate', $employee->employee_id) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold text-xs shadow-md transition-all duration-200">
                                                <i class='bx bx-show mr-1'></i> View
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <i class='bx bx-clipboard text-5xl text-gray-300 mb-3'></i>
                                        <p class="text-lg font-medium">No employees pending hands-on evaluation</p>
                                        <p class="text-sm text-gray-400 mt-2">Employees must first pass the Step 1 quiz evaluation in Assessment Results before appearing here.</p>
                                        <a href="{{ route('assessment.results') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                                            <i class='bx bx-list-check mr-2'></i> Go to Assessment Results
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
