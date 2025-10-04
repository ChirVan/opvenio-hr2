<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

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
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Performance Score Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-blue-100 text-sm font-medium">Performance Score</h3>
                        <p class="text-3xl font-bold mt-2">87%</p>
                        <p class="text-blue-100 text-sm mt-1">Above average</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2L13.09 8.26L20 9L15 13.74L16.18 20.02L10 16.77L3.82 20.02L5 13.74L0 9L6.91 8.26L10 2Z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Employees Card -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-green-100 text-sm font-medium">Active Employees</h3>
                        <p class="text-3xl font-bold mt-2">1,234</p>
                        <p class="text-green-100 text-sm mt-1">Currently employed</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Employees Card -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-purple-100 text-sm font-medium">Total Employees</h3>
                        <p class="text-3xl font-bold mt-2">1,234</p>
                        <p class="text-purple-100 text-sm mt-1">Active employees</p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Hires -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Hires</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">Maria Santos</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">HR Specialist</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">2025-09-01</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Active</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">James Lee</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">Training Coordinator</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">2025-08-20</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Active</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">Nina Gupta</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">Payroll Analyst</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">2025-07-15</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Probation</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Upcoming Trainings -->
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
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enrolled</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">Leadership 101</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">2025-10-05</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">30</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">22</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">Time Management</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">2025-10-12</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">25</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">18</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">Payroll Best Practices</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">2025-11-03</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">20</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">12</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
