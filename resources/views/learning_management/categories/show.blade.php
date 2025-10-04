<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $category->category_name }}</h1>
                        <p class="text-gray-600 mt-2">{{ $category->description }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('learning.assessment.categories.edit', $category) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-edit mr-2'></i>
                            Edit Category
                        </a>
                        <a href="#" onclick="createAssessment()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-plus mr-2'></i>
                            Create Assessment
                        </a>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb Navigation -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('learning.assessment') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Assessment Center
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class='bx bx-chevron-right text-gray-400'></i>
                            <span class="ml-1 text-gray-900 font-medium">{{ $category->category_name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Category Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class='bx bx-book-bookmark text-gray-400 text-2xl'></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Assessments</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $category->assessments->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class='bx bx-check-circle text-green-400 text-2xl'></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Active Assessments</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $category->assessments->where('is_active', true)->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class='bx bx-help-circle text-blue-400 text-2xl'></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Questions</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $category->assessments->sum(function($assessment) { return $assessment->questions->count(); }) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Category Status</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $category->is_active ? 'Available' : 'Disabled' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessments List -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Assessments in this Category</h2>
                    @if($category->assessments->count() > 0)
                        <p class="text-gray-600">Manage and view all assessments in the {{ $category->category_name }} category</p>
                    @else
                        <p class="text-gray-600">No assessments have been created in this category yet</p>
                    @endif
                </div>

                @forelse($category->assessments as $assessment)
                    <div class="border border-gray-200 rounded-lg p-6 mb-4 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <h3 class="text-lg font-semibold text-gray-900 mr-3">{{ $assessment->title }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $assessment->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $assessment->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <p class="text-gray-600 mt-2">{{ Str::limit($assessment->description, 120) }}</p>
                                <div class="flex items-center mt-3 text-sm text-gray-500">
                                    <i class='bx bx-help-circle mr-1'></i>
                                    <span class="mr-4">{{ $assessment->questions->count() }} Questions</span>
                                    <i class='bx bx-time mr-1'></i>
                                    <span class="mr-4">{{ $assessment->time_limit ?? 'No time limit' }}</span>
                                    <i class='bx bx-calendar mr-1'></i>
                                    <span>Created {{ $assessment->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <a href="#" onclick="viewAssessment({{ $assessment->id }})" class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class='bx bx-show mr-1'></i>
                                    View
                                </a>
                                <a href="#" onclick="editAssessment({{ $assessment->id }})" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class='bx bx-edit mr-1'></i>
                                    Edit
                                </a>
                                <button onclick="deleteAssessment({{ $assessment->id }})" class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class='bx bx-trash mr-1'></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i class='bx bx-file-blank text-gray-300 text-6xl mb-4'></i>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">No Assessments Found</h3>
                        <p class="text-gray-500 mb-6">Start building assessments for this category to evaluate skills and knowledge.</p>
                        <button onclick="createAssessment()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md inline-flex items-center">
                            <i class='bx bx-plus mr-2'></i>
                            Create First Assessment
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function createAssessment() {
            // Redirect to quiz creation form
            window.location.href = '{{ route("learning.quiz") }}';
        }

        function viewAssessment(id) {
            alert(`View assessment ${id} - functionality coming soon!`);
        }

        function editAssessment(id) {
            alert(`Edit assessment ${id} - functionality coming soon!`);
        }

        function deleteAssessment(id) {
            if (confirm('Are you sure you want to delete this assessment? This action cannot be undone.')) {
                alert(`Delete assessment ${id} - functionality coming soon!`);
            }
        }
    </script>
</x-app-layout>