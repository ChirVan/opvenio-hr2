<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-t-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-1">System Audit Logs</h1>
                        <p class="text-green-100">Track and monitor all system activities</p>
                    </div>
                </div>
            </div>

            <!-- Tabs Container -->
            <div class="bg-white rounded-b-lg shadow-lg" x-data="{ activeTab: 'login' }">
                <!-- Tab Headers -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-4 px-6 pt-4">
                        <button
                            class="py-2 px-4 text-sm font-medium focus:outline-none"
                            :class="activeTab === 'login' ? 'border-b-2 border-green-600 text-green-700' : 'text-gray-500 hover:text-green-700'"
                            @click="activeTab = 'login'">
                            Login Logs
                        </button>
                        <button
                            class="py-2 px-4 text-sm font-medium focus:outline-none"
                            :class="activeTab === 'activity' ? 'border-b-2 border-green-600 text-green-700' : 'text-gray-500 hover:text-green-700'"
                            @click="activeTab = 'activity'">
                            Activity Logs
                        </button>
                    </nav>
                </div>

                <!-- LOGIN LOGS TAB -->
                <div x-show="activeTab === 'login'" class="p-6" x-cloak>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Time In</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Time Out</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Details</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50 transition text-xs align-middle">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center {{ $log->status === 'Failed' ? 'bg-red-100' : ($log->status === 'Success' ? 'bg-green-100' : 'bg-gray-100') }}">
                                                    <span class="font-semibold {{ $log->status === 'Failed' ? 'text-red-600' : ($log->status === 'Success' ? 'text-green-600' : 'text-gray-600') }}">
                                                        {{ strtoupper(substr($log->user_name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $log->user_name)[1] ?? '', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $log->user_name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $log->employee_email }}</div>
                                                </div>
                                            </div>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            @if($log->time_in)
                                                {{ \Carbon\Carbon::parse($log->time_in)->setTimezone('Asia/Manila')->format('M d, Y') }}<br>
                                                <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($log->time_in)->setTimezone('Asia/Manila')->format('h:i A') }}</span>
                                            @else
                                                <span class="text-gray-300 text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            @if($log->time_out)
                                                {{ \Carbon\Carbon::parse($log->time_out)->setTimezone('Asia/Manila')->format('M d, Y') }}<br>
                                                <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($log->time_out)->setTimezone('Asia/Manila')->format('h:i A') }}</span>
                                            @else
                                                <span class="text-gray-300 text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500">{{ $log->details }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full {{ $log->status === 'Failed' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $log->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-center text-gray-400 text-xs">
                                            No audit logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4 flex justify-center">
                            {{ $logs->onEachSide(1)->links('vendor.pagination.custom-tailwind') }}
                        </div>
                    </div>
                </div>

                <!-- ACTIVITY LOGS TAB -->
                <div x-show="activeTab === 'activity'" class="p-6" x-cloak>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Activity</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Details</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Created At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($activityLogs as $activity)
                                    <tr class="hover:bg-gray-50 transition text-xs align-middle">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center bg-green-100">
                                                    <span class="font-semibold text-green-600">
                                                        {{ strtoupper(substr($activity->user_name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $activity->user_name)[1] ?? '', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $activity->user_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-900">
                                            @php
                                                $label = $activity->activity_label ?? null;
                                                $activityType = $activity->activity ?? $activity->activity_type ?? null;
                                                // Map for both legacy and new logs
                                                if (!$label) {
                                                    if ($activityType === 'create_training_catalog' || $activityType === 'Create') $label = 'Create';
                                                    elseif ($activityType === 'update_training_catalog' || $activityType === 'Edit') $label = 'Edit';
                                                    elseif ($activityType === 'delete_training_catalog' || $activityType === 'Delete') $label = 'Delete';
                                                    else $label = $activityType;
                                                }
                                            @endphp
                                            @if($label === 'Edit')
                                                <span style="background-color: #eaf4ff; color: #1a56db; padding: 4px 14px; border-radius: 16px; font-weight: 500; font-size: 12px; display: inline-block;">Edit</span>
                                            @elseif($label === 'Delete')
                                                <span style="background-color: #ffeaea; color: #d32f2f; padding: 4px 14px; border-radius: 16px; font-weight: 500; font-size: 12px; display: inline-block;">Delete</span>
                                            @elseif($label === 'Create')
                                                <span style="background-color: #c6f6d5; color: #256029; padding: 4px 14px; border-radius: 16px; font-weight: 500; font-size: 12px; display: inline-block;">Create</span>
                                            @else
                                                <span class="px-3 py-1 rounded-full font-semibold {{ $activity->activity_class }}">{{ $label }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500">{{ $activity->details }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full {{ $activity->status === 'Failed' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $activity->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                                            {{ \Carbon\Carbon::parse($activity->created_at)->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-center text-gray-400 text-xs">
                                            No activity logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4 flex justify-center">
                            {{ $activityLogs->onEachSide(1)->links('vendor.pagination.custom-tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .pagination {
            display: flex;
            gap: 4px;
            justify-content: center;
            margin-top: 8px;
        }
        .pagination .page-link {
            padding: 4px 10px;
            font-size: 0.75rem;
            border-radius: 4px;
            background: #f3f4f6;
            color: #256029;
            border: 1px solid #d1d5db;
            transition: background 0.2s, color 0.2s;
        }
        .pagination .page-link:hover {
            background: #256029;
            color: #fff;
        }
        .pagination .active .page-link {
            background: #256029;
            color: #fff;
            font-weight: bold;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>
