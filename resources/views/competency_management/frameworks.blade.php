<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Competencies</h1>
                <p class="mt-2 text-sm text-gray-600">Manage and track competencies across your organization</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg mb-6 shadow-sm animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-green-800 font-medium">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700 transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 shadow-sm animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-red-800 font-medium">{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700 transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Action Bar -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Left Section: Count and Sort -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                        <!-- Sort Dropdowns -->
                        <form method="GET" action="{{ route('competency.frameworks') }}" class="flex flex-wrap gap-3 items-end">
                            <!-- Search Name Field -->
                            <div class="flex flex-col">
                                <label for="competency_search" class="text-xs font-medium text-gray-700 mb-1">Search Name:</label>
                                <input type="text" name="competency_search" id="competency_search" 
                                       value="{{ request('competency_search') }}" 
                                       placeholder="Search name..." 
                                       class="border border-gray-300 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-green-400 w-40">
                            </div>
                            
                            <!-- Competency Name Sort -->
                            <div class="flex flex-col">
                                <label for="sort_name" class="text-xs font-medium text-gray-700 mb-1">Sort Name:</label>
                                <select name="sort_name" id="sort_name" class="border border-gray-300 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-green-400 w-24">
                                    <option value="">Default</option>
                                    <option value="az" {{ request('sort_name') == 'az' ? 'selected' : '' }}>A-Z</option>
                                    <option value="za" {{ request('sort_name') == 'za' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </div>
                            
                            <!-- Category Filter -->
                            <div class="flex flex-col">
                                <label for="category" class="text-xs font-medium text-gray-700 mb-1">Categories:</label>
                                <select name="category" id="category" class="border border-gray-300 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-green-400 w-32">
                                    <option value="">All Categories</option>
                                    @php $categories = $competencies->pluck('framework.framework_name')->unique()->filter(); @endphp
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Status Filter -->
                            <div class="flex flex-col">
                                <label for="status" class="text-xs font-medium text-gray-700 mb-1">Status:</label>
                                <select name="status" id="status" class="border border-gray-300 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-green-400 w-24">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-md transition-colors inline-flex items-center justify-center" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-title="Filter">
                                <i class='bx bx-search text-sm'></i>
                            </button>
                            <a href="{{ route('competency.frameworks') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-md transition-colors inline-flex items-center justify-center" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               data-bs-title="Reset">
                                <i class='bx bx-refresh text-sm'></i>
                            </a>
                        </form>
                    </div>

                    <!-- Right Section: Create Button -->
                    <div>
                        <a href="{{ route('competency.competencies.create') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5" 
                           data-bs-toggle="tooltip" 
                           data-bs-placement="top" 
                           data-bs-title="Create Competency ">
                            <i class='bx bx-plus text-lg mr-2'></i>
                            Create Competency
                        </a>
                    </div>
                </div>
            </div>

            <!-- Competencies Table -->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">#ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Competency Name</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Proficiency</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-green-800 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($competencies as $i => $competency)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center bg-green-100 text-green-800 text-sm font-semibold rounded-full px-3 py-1">
                                            {{ $competency->competency_id ?? ($i + 1) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $competency->competency_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $competency->framework->framework_name ?? 'No Category' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $level = $competency->proficiency_levels;
                                            if ($level == 1) {
                                                $label = 'Basic';
                                                $color = 'bg-yellow-100 text-yellow-800';
                                            } elseif ($level == 2) {
                                                $label = 'Intermediate';
                                                $color = 'bg-blue-100 text-blue-800';
                                            } else {
                                                $label = 'Expert';
                                                $color = 'bg-green-100 text-green-800';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="{{ $competency->status_color }} px-3 py-1 rounded-full text-xs font-semibold">
                                            {{ ucfirst($competency->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('competency.competencies.show', $competency) }}" 
                                               class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-all duration-200 transform hover:scale-110" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               data-bs-title="View">
                                                <i class='bx bx-show text-lg'></i>
                                            </a>
                                            <a href="{{ route('competency.competencies.edit', $competency) }}" 
                                               class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-900 transition-all duration-200 transform hover:scale-110" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               data-bs-title="Edit">
                                                <i class='bx bx-edit-alt text-lg'></i>
                                            </a>
                                            <form method="POST" action="{{ route('competency.competencies.destroy', $competency) }}" class="inline delete-competency-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-900 transition-all duration-200 transform hover:scale-110 delete-competency-btn" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="Delete">
                                                    <i class='bx bx-trash text-lg'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($competencies->isEmpty())
                                <tr>
                                    <td colspan="6" class="px-6 py-16">
                                        <div class="text-center">
                                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No competencies found</h3>
                                            <p class="text-gray-500 mb-4">Get started by creating your first competency</p>
                                            <a href="{{ route('competency.competencies.create') }}" 
                                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow transition-colors">
                                                <i class='bx bx-plus text-lg mr-2'></i>
                                                Create Competency
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION SECTION -->
                @if($competencies->count() > 0)
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <!-- Mobile Pagination -->
                            <div class="flex-1 flex justify-between sm:hidden">
                                @if ($competencies->hasPages())
                                    @if ($competencies->onFirstPage())
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 border border-gray-300 cursor-not-allowed rounded-lg shadow-sm">
                                            <i class='bx bx-chevron-left mr-1'></i>
                                            Previous
                                        </span>
                                    @else
                                        <a href="{{ $competencies->appends(request()->query())->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition-colors">
                                            <i class='bx bx-chevron-left mr-1'></i>
                                            Previous
                                        </a>
                                    @endif

                                    @if ($competencies->hasMorePages())
                                        <a href="{{ $competencies->appends(request()->query())->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition-colors">
                                            Next
                                            <i class='bx bx-chevron-right ml-1'></i>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 border border-gray-300 cursor-not-allowed rounded-lg shadow-sm">
                                            Next
                                            <i class='bx bx-chevron-right ml-1'></i>
                                        </span>
                                    @endif
                                @else
                                    <!-- Show placeholder when only one page -->
                                    <div class="text-sm text-gray-500">Page 1 of 1</div>
                                @endif
                            </div>
                            
                            <!-- Desktop Pagination -->
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                                    <p class="text-sm text-gray-600">
                                        Showing
                                        <span class="font-semibold text-green-600">{{ $competencies->firstItem() ?? 1 }}</span>
                                        to
                                        <span class="font-semibold text-green-600">{{ $competencies->lastItem() ?? $competencies->count() }}</span>
                                        of
                                        <span class="font-semibold text-green-600">{{ $competencies->total() }}</span>
                                        results
                                    </p>
                                </div>
                                <div class="pagination-wrapper">
                                    <nav aria-label="Competencies pagination">
                                        <ul class="pagination">
                                            {{-- Always show pagination structure --}}
                                            @if ($competencies->hasPages())
                                                {{-- Previous Page Link --}}
                                                @if ($competencies->onFirstPage())
                                                    <li class="page-item disabled" aria-disabled="true">
                                                        <span class="page-link">
                                                            <i class='bx bx-chevron-left'></i>
                                                        </span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $competencies->appends(request()->query())->previousPageUrl() }}" rel="prev">
                                                            <i class='bx bx-chevron-left'></i>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @foreach ($competencies->getUrlRange(1, $competencies->lastPage()) as $page => $url)
                                                    @if ($page == $competencies->currentPage())
                                                        <li class="page-item active" aria-current="page">
                                                            <span class="page-link">{{ $page }}</span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link" href="{{ $competencies->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($competencies->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $competencies->appends(request()->query())->nextPageUrl() }}" rel="next">
                                                            <i class='bx bx-chevron-right'></i>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item disabled" aria-disabled="true">
                                                        <span class="page-link">
                                                            <i class='bx bx-chevron-right'></i>
                                                        </span>
                                                    </li>
                                                @endif
                                            @else
                                                {{-- Single page scenario --}}
                                                <li class="page-item disabled" aria-disabled="true">
                                                    <span class="page-link">
                                                        <i class='bx bx-chevron-left'></i>
                                                    </span>
                                                </li>
                                                <li class="page-item active" aria-current="page">
                                                    <span class="page-link">1</span>
                                                </li>
                                                <li class="page-item disabled" aria-disabled="true">
                                                    <span class="page-link">
                                                        <i class='bx bx-chevron-right'></i>
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Bootstrap Pagination Custom Styles */
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.375rem;
            gap: 0.25rem;
        }

        .page-item {
            position: relative;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: 0;
            line-height: 1.25;
            color: #6b7280;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .page-link:hover {
            z-index: 2;
            color: #059669;
            background-color: #f3f4f6;
            border-color: #9ca3af;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .page-link:focus {
            z-index: 3;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
        }

        .page-item:not(:first-child) .page-link {
            margin-left: 0;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff !important;
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            border-color: #047857 !important;
            font-weight: 700 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px -2px rgba(5, 150, 105, 0.4), 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
        }

        .page-item.active .page-link:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%) !important;
            border-color: #065f46 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px -4px rgba(5, 150, 105, 0.5), 0 0 0 3px rgba(5, 150, 105, 0.15) !important;
        }

        .page-item.disabled .page-link {
            color: #9ca3af;
            pointer-events: none;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Ensure proper spacing for pagination wrapper */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Delete confirmation
            document.querySelectorAll('.delete-competency-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = btn.closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>