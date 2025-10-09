<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6 px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-t-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Promotion Recommendation Form</h1>
                        <p class="text-green-100">Employee Advancement Assessment</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                        <div class="text-sm font-medium">Application ID</div>
                        <div class="text-2xl font-bold">#{{ str_pad($talent->employee_id ?? '000', 4, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="bg-white rounded-b-lg shadow-lg">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-t-lg" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-t-lg" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-t-lg">
                        <strong class="font-bold">Please fix the following errors:</strong>
                        <ul class="list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('succession.promote') }}">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $talent->employee_id ?? request('employee_id') }}">

                    <!-- Section 1: Employee Information -->
                    <div class="border-b border-gray-200">
                        <div class="bg-gray-50 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Employee Information
                            </h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-800">
                                    {{ $talent->employee_name ?? 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-800">
                                    {{ $talent->employee_email ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Position Details -->
                    <div class="border-b border-gray-200">
                        <div class="bg-gray-50 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Position Details
                            </h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Position</label>
                                <input type="text" name="job_title" value="{{ $talent->job_title ?? '' }}" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Proposed Position <span class="text-red-500">*</span></label>
                                <select name="potential_job" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                    <option value="">-- Select Position --</option>
                                    <option value="HR Manager" {{ (isset($talent->potential_job) && $talent->potential_job == 'HR Manager') ? 'selected' : '' }}>HR Manager</option>
                                    <option value="IT Manager" {{ (isset($talent->potential_job) && $talent->potential_job == 'IT Manager') ? 'selected' : '' }}>IT Manager</option>
                                    <option value="Finance Officer" {{ (isset($talent->potential_job) && $talent->potential_job == 'Finance Officer') ? 'selected' : '' }}>Finance Officer</option>
                                    <option value="Operations Supervisor" {{ (isset($talent->potential_job) && $talent->potential_job == 'Operations Supervisor') ? 'selected' : '' }}>Operations Supervisor</option>
                                    <option value="Senior Developer" {{ (isset($talent->potential_job) && $talent->potential_job == 'Senior Developer') ? 'selected' : '' }}>Senior Developer</option>
                                    <option value="Team Leader" {{ (isset($talent->potential_job) && $talent->potential_job == 'Team Leader') ? 'selected' : '' }}>Team Leader</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Assessment Results -->
                    <div class="border-b border-gray-200">
                        <div class="bg-gray-50 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Assessment Results
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Assessment Title</label>
                                    <div class="text-lg font-semibold text-gray-800">
                                        {{ $talent->quiz_title ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                    <div class="text-lg font-semibold text-gray-800">
                                        {{ $talent->category_name ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="bg-teal-50 rounded-lg p-4 border border-teal-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Overall Score</label>
                                    <div class="text-3xl font-bold text-green-600">
                                        {{ $talent->average_score ?? '0' }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Evaluation -->
                    <div class="border-b border-gray-200">
                        <div class="bg-gray-50 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Professional Evaluation
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Key Strengths & Competencies
                                    <span class="text-gray-500 text-xs ml-1">(Highlight employee's core capabilities)</span>
                                </label>
                                <textarea name="strengths" rows="5" 
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent transition resize-none"
                                          placeholder="Example: Demonstrates exceptional leadership skills, consistently exceeds performance targets, strong problem-solving abilities...">{{ $talent->evaluation_data['strengths'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Development Recommendations
                                    <span class="text-gray-500 text-xs ml-1">(Suggested areas for growth and training)</span>
                                </label>
                                <textarea name="recommendations" rows="5" 
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent transition resize-none"
                                          placeholder="Example: Recommend advanced leadership training, cross-functional project experience, mentorship program participation...">{{ $talent->evaluation_data['recommendations'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 px-6 py-4 rounded-b-lg">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                <span class="text-red-500">*</span> Required fields must be completed
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('succession.talent-pool') }}" 
                                   class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center gap-2 shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Submit Promotion Recommendation
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Custom styles for better form appearance */
        input[type="text"]:read-only,
        textarea:read-only {
            background-color: #f9fafb;
            cursor: not-allowed;
        }
        
        select option:first-child {
            color: #9ca3af;
        }
        
        /* Smooth transitions */
        input, select, textarea, button, a {
            transition: all 0.2s ease;
        }
        
        /* Focus states */
        input:focus, select:focus, textarea:focus {
            outline: none;
        }
    </style>
</x-app-layout>