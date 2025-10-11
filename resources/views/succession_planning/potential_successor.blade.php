

<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6 px-4">
        <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-t-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Potential Successors</h1>
                    <p class="text-green-100">Approved Employees Overview</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-b-lg shadow-lg">
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 shadow-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Employee ID</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Potential Job</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($approvedEmployees as $employee)
                                <form method="POST" action="{{ route('succession.promote') }}">
                                    @csrf
                                    <tr class="hover:bg-green-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-700">
                                            <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                                            {{ $employee->employee_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">
                                            <input type="hidden" name="employee_name" value="{{ $employee->full_name }}">
                                            {{ $employee->full_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                            <input type="hidden" name="employee_email" value="{{ $employee->email ?? '' }}">
                                            {{ $employee->email ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                            <input type="hidden" name="job_title" value="{{ $employee->job_title }}">
                                            {{ $employee->job_title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                            <select name="potential_job" class="border rounded px-2 py-1 w-full" required>
                                                <option value="">Select Potential Job</option>
                                                <option value="HR Manager">HR Manager</option>
                                                <option value="Payroll Administrator">Payroll Administrator</option>
                                                <option value="Senior IT Specialist">Senior IT Specialist</option>
                                                <option value="Software Engineer">Software Engineer</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" name="assessment_score" value="0.0">
                                            <input type="hidden" name="category" value="Leadership">
                                            <input type="hidden" name="strengths" value="">
                                            <input type="hidden" name="recommendations" value="">
                                            <input type="hidden" name="status" value="pending">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $employee->status == 'passed' ? 'bg-green-200 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($employee->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(in_array($employee->employee_id, $promotedIds))
                                                <button type="button" class="px-4 py-2 bg-gray-400 text-white rounded-lg font-semibold text-xs shadow transition flex items-center gap-2" disabled>
                                                    <i class='bx bx-check-circle text-white'></i> Sent
                                                </button>
                                            @else
                                                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold text-xs shadow transition flex items-center gap-2">
                                                    <i class='bx bx-upload text-white'></i> Promote
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                </form>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No approved employees found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-successors th {
            background: linear-gradient(90deg, #e0f7fa 0%, #e8f5e9 100%);
            color: #256029;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        .table-successors td {
            border-bottom: 1px solid #e5e7eb;
        }
        .table-successors tr:hover {
            background-color: #e6ffe6;
        }
        .table-successors .badge {
            padding: 0.4em 0.8em;
            border-radius: 999px;
            font-size: 0.85em;
            font-weight: 600;
        }
        .table-successors .badge-approved {
            background: #bbf7d0;
            color: #166534;
        }
        .table-successors .badge-pending {
            background: #fef9c3;
            color: #92400e;
        }
    </style>
</x-app-layout>