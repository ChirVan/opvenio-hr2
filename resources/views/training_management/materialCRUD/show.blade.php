<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <!-- Breadcrumb -->
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('training.catalog.index') }}" class="text-gray-500 hover:text-gray-700">
                                <i class='bx bx-home mr-1'></i>
                                Training Catalog
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <a href="{{ route('training.catalog.detail', $catalog) }}" class="ml-1 text-gray-500 hover:text-gray-700">
                                    {{ $catalog->title }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <span class="ml-1 text-gray-900 font-medium">{{ $material->lesson_title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Success/Error Messages -->
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

                <!-- Header Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-book-open text-blue-600 text-3xl'></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $material->lesson_title }}</h1>
                                <div class="flex items-center space-x-4 mb-3">
                                    @if($material->competency)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class='bx bx-target-lock mr-1'></i>
                                            {{ $material->competency->competency_name }}
                                            @if($material->competency->framework)
                                                ({{ $material->competency->framework->framework_name }})
                                            @endif
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class='bx bx-trophy mr-1'></i>
                                        Level {{ $material->proficiency_level }} - {{ $material->proficiency_level_name }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <span>Created {{ $material->created_at->format('F j, Y \a\t g:i A') }}</span>
                                    @if($material->updated_at != $material->created_at)
                                        <span class="ml-4">Last updated {{ $material->updated_at->format('F j, Y \a\t g:i A') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="{{ $material->status_color }}">
                                {{ ucfirst($material->status) }}
                            </span>
                            <div class="flex space-x-2 ml-4">
                                <a href="{{ route('training.materials.edit', [$catalog, $material]) }}" 
                                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors">
                                    <i class='bx bx-edit mr-2'></i>
                                    Edit Material
                                </a>
                                
                                @if($material->status === 'draft')
                                    <form method="POST" action="{{ route('training.materials.publish', [$catalog, $material]) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors">
                                            <i class='bx bx-check-circle mr-2'></i>
                                            Publish
                                        </button>
                                    </form>
                                @elseif($material->status === 'published')
                                    <form method="POST" action="{{ route('training.materials.archive', [$catalog, $material]) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors">
                                            <i class='bx bx-archive mr-2'></i>
                                            Archive
                                        </button>
                                    </form>
                                @elseif($material->status === 'archived')
                                    <form method="POST" action="{{ route('training.materials.publish', [$catalog, $material]) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors">
                                            <i class='bx bx-refresh mr-2'></i>
                                            Publish Again
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('training.catalog.detail', $catalog) }}" 
                                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors">
                                    <i class='bx bx-arrow-back mr-2'></i>
                                    Back to Catalog
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lesson Content Section -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Lesson Content</h2>
                    <div class="prose max-w-none">
                        <div class="lesson-content">
                            {!! $material->lesson_content !!}
                        </div>
                    </div>
                </div>

                <!-- Material Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Competency Information -->
                    @if($material->competency)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-blue-900 mb-3">
                                <i class='bx bx-target-lock mr-2'></i>
                                Competency Details
                            </h3>
                            <div class="space-y-2 text-sm">
                                <div><strong>Competency:</strong> {{ $material->competency->competency_name }}</div>
                                @if($material->competency->framework)
                                    <div><strong>Framework:</strong> {{ $material->competency->framework->framework_name }}</div>
                                @endif
                                <div><strong>Proficiency Level:</strong> Level {{ $material->proficiency_level }} - {{ $material->proficiency_level_name }}</div>
                                @if($material->competency->description)
                                    <div class="mt-3">
                                        <strong>Competency Description:</strong>
                                        <p class="text-gray-700 mt-1">{{ $material->competency->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Material Metadata -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class='bx bx-info-circle mr-2'></i>
                            Material Information
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div><strong>Status:</strong> <span class="{{ $material->status_color }}">{{ ucfirst($material->status) }}</span></div>
                            <div><strong>Training Catalog:</strong> {{ $material->trainingCatalog->title }}</div>
                            <div><strong>Created:</strong> {{ $material->created_at->format('F j, Y \a\t g:i A') }}</div>
                            @if($material->updated_at != $material->created_at)
                                <div><strong>Last Updated:</strong> {{ $material->updated_at->format('F j, Y \a\t g:i A') }}</div>
                            @endif
                            <div><strong>Content Length:</strong> {{ strlen(strip_tags($material->lesson_content)) }} characters</div>
                        </div>
                    </div>
                </div>

                <!-- Actions Section -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('training.materials.edit', [$catalog, $material]) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors">
                            <i class='bx bx-edit mr-2'></i>
                            Edit Material
                        </a>

                        @if($material->status === 'draft')
                            <form method="POST" action="{{ route('training.materials.publish', [$catalog, $material]) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors"
                                        onclick="return confirm('Are you sure you want to publish this training material?')">
                                    <i class='bx bx-check-circle mr-2'></i>
                                    Publish Material
                                </button>
                            </form>
                        @endif

                        @if($material->status === 'published')
                            <form method="POST" action="{{ route('training.materials.archive', [$catalog, $material]) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors"
                                        onclick="return confirm('Are you sure you want to archive this training material?')">
                                    <i class='bx bx-archive mr-2'></i>
                                    Archive Material
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('training.materials.destroy', [$catalog, $material]) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this training material? This action cannot be undone.')">
                                <i class='bx bx-trash mr-2'></i>
                                Delete Material
                            </button>
                        </form>

                        <a href="{{ route('training.catalog.detail', $catalog) }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Back to Catalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Lesson Content Styling */
        .lesson-content {
            line-height: 1.6;
        }
        
        .lesson-content h1 {
            font-size: 2em;
            font-weight: bold;
            margin: 1em 0 0.5em 0;
            color: #1f2937;
        }
        
        .lesson-content h2 {
            font-size: 1.5em;
            font-weight: bold;
            margin: 1em 0 0.5em 0;
            color: #374151;
        }
        
        .lesson-content h3 {
            font-size: 1.25em;
            font-weight: bold;
            margin: 1em 0 0.5em 0;
            color: #4b5563;
        }
        
        .lesson-content p {
            margin: 1em 0;
            color: #374151;
        }
        
        .lesson-content ul, .lesson-content ol {
            margin: 1em 0;
            padding-left: 2em;
        }
        
        .lesson-content li {
            margin: 0.5em 0;
            color: #374151;
        }
        
        .lesson-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5em 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .lesson-content strong, .lesson-content b {
            font-weight: 600;
            color: #1f2937;
        }
        
        .lesson-content em, .lesson-content i {
            font-style: italic;
        }
        
        .lesson-content u {
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .lesson-content h1 {
                font-size: 1.75em;
            }
            
            .lesson-content h2 {
                font-size: 1.375em;
            }
            
            .lesson-content h3 {
                font-size: 1.125em;
            }
        }
    </style>
</x-app-layout>