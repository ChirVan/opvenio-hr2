<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    @php
        // Fetch employee data from the controller
        $totalEmployees = $totalEmployees ?? 0;
        $activeEmployees = $activeEmployees ?? 0;
        $recentHires = $recentHires ?? [];
    @endphp

    <div class="py-3">
        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif

        <!-- Dashboard Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>

            <p class="text-gray-600 mt-1">Welcome back! Here's what's happening with your HR system.</p>

            {{-- Sync with HR4 Button --}}
            <form id="syncForm" method="POST" action="javascript:void(0);">
                @csrf
                <button id="syncBtn" type="button" class="px-4 py-2 bg-indigo-600 text-white rounded">
                    🔄 Sync Employees (HR4)
                </button>
            </form>
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Courses Card -->
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-yellow-100 text-sm font-medium">Total Courses</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($totalCourses ?? 0) }}</p>
                        <p class="text-yellow-100 text-sm mt-1">Available Materials: {{ number_format($availableCourses ?? 0) }}</p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3 flex items-center justify-center">
                        <i class='bx bx-book-open text-yellow-700 text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Assigned Employee Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-blue-100 text-sm font-medium">Assigned Employee</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($assignedEmployees ?? 0) }}</p>
                        <p class="text-blue-100 text-sm mt-1">Employees with assignments</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3 flex items-center justify-center">
                        <i class='bx bx-user-check text-blue-700 text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Identified Successors Card -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-green-100 text-sm font-medium">Identified Successors</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($identifiedSuccessors ?? 0) }}</p>
                        <p class="text-green-100 text-sm mt-1">Talent pool</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3 flex items-center justify-center">
                        <i class='bx bx-group text-green-700 text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Re-evaluation Employee Card -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-red-100 text-sm font-medium">Re-evaluation Employee</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($reevaluationEmployees ?? 0) }}</p>
                        <p class="text-red-100 text-sm mt-1">Pending review</p>
                    </div>
                    <div class="bg-red-400 bg-opacity-30 rounded-full p-3 flex items-center justify-center">
                        <i class='bx bx-error text-red-700 text-3xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Potential Successors -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Potential Successors</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 shadow-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Employee ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Position</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($approvedEmployees as $employee)
                                    <tr class="hover:bg-green-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-700">
                                            {{ $employee->employee_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">
                                            {{ $employee->full_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                            {{ $employee->job_title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $employee->status == 'passed' ? 'bg-green-200 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($employee->status) }}
                                            </span>
                                        </td>
                                    </tr>
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

            <!-- Upcoming Trainings (static for now) -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Upcoming Trainings</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slots</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-gray-500">No upcoming trainings found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
document.getElementById('syncBtn').addEventListener('click', async () => {
    if (!confirm('Sync employees from HR4? This will update/create accounts.')) return;

    // Use AbortController to guard against calls hanging
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 30000); // 30s client-side timeout

    // Disable button and show loading state
    const btn = document.getElementById('syncBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '⏳ Syncing...';

    try {
        const res = await fetch('{{ route("sync.employees.hr4") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            signal: controller.signal,
        });

        clearTimeout(timeout);

        const data = await res.json();

        if (!res.ok) {
            alert('Sync failed: ' + res.status + '\n' + (data.message || JSON.stringify(data)));
            window.location.href = '/dashboard';
            return;
        }

        if (!data.success) {
            alert('Sync failed: ' + (data.message || 'Unknown error'));
            window.location.href = '/dashboard';
            return;
        }

        // Build a detailed summary message
        let msg = 'Sync finished.\n';
        if (data.no_changes) {
            msg += 'No changes — database already synchronized.\n';
        } else {
            msg += `Created: ${data.created || 0}\nUpdated: ${data.updated || 0}\nSkipped: ${data.skipped || 0}\nErrors: ${data.errors || 0}\n`;
        }

        // Show error details if any
        if (data.error_details && data.error_details.length > 0) {
            msg += '\nError details:\n' + data.error_details.slice(0, 5).join('\n');
            if (data.error_details.length > 5) {
                msg += '\n... and ' + (data.error_details.length - 5) + ' more errors (check logs)';
            }
        }

        alert(msg);
    } catch (err) {
        if (err.name === 'AbortError') {
            alert('Sync timed out (client). Please try again.');
        } else {
            alert('Sync error: ' + (err.message || 'Unknown error'));
        }
    } finally {
        // Restore button and refresh page
        btn.disabled = false;
        btn.innerHTML = originalText;
        window.location.href = '/dashboard';
    }
});
</script>
</x-app-layout>
