<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                <span class="ml-1 text-gray-900 font-medium">{{ $catalog->title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-book text-blue-600 text-3xl'></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $catalog->title }}</h1>
                                <p class="text-lg text-gray-600 mb-2">{{ $catalog->label }}</p>
                                @if($catalog->framework)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class='bx bx-target-lock mr-1'></i>
                                        {{ $catalog->framework->framework_name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $catalog->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $catalog->status_text }}
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('training.catalog.edit', $catalog) }}" 
                                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors">
                                    <i class='bx bx-edit mr-2'></i>
                                    Edit Catalog
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                @if($catalog->description)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $catalog->description }}</p>
                    </div>
                @endif

                <!-- Training Materials Section -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Training Materials</h2>
                            <p class="text-gray-600 mt-1">
                                Manage training resources and content for this catalog
                                @if($trainingMaterials->count() > 0)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $trainingMaterials->count() }} {{ $trainingMaterials->count() === 1 ? 'Material' : 'Materials' }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('training.material.create', $catalog) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md inline-flex items-center transition-colors font-medium">
                            <i class='bx bx-plus mr-2'></i>
                            Create Training Material
                        </a>
                    </div>

                    @forelse($trainingMaterials as $material)
                        <!-- Training Materials List -->
                        @if($loop->first)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @endif
                        
                        <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $material->lesson_title }}</h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                        @if($material->competency)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $material->competency->competency_name }}
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Level {{ $material->proficiency_level }} - {{ $material->proficiency_level_name }}
                                        </span>
                                    </div>
                                </div>
                                <span class="{{ $material->status_color }}">
                                    {{ ucfirst($material->status) }}
                                </span>
                            </div>
                            
                            <div class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $material->content_excerpt }}
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="text-xs text-gray-500">
                                    Created {{ $material->created_at->format('M d, Y') }}
                                </div>
                                <div class="flex space-x-3">
                                    <a href="{{ route('training.materials.show', [$catalog, $material]) }}" 
                                       class="text-blue-600 hover:text-blue-800 transition-colors" 
                                       title="View">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    <a href="{{ route('training.materials.edit', [$catalog, $material]) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 transition-colors" 
                                       title="Edit">
                                        <i class='bx bx-edit text-lg'></i>
                                    </a>
                                    @if($material->status === 'draft')
                                        <form method="POST" action="{{ route('training.materials.publish', [$catalog, $material]) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 transition-colors" title="Publish">
                                                <i class='bx bx-check-circle text-lg'></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('training.materials.destroy', [$catalog, $material]) }}" 
                                          onsubmit="return confirm('Are you sure you want to delete this training material?')" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Delete">
                                            <i class='bx bx-trash text-lg'></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        @if($loop->last)
                            </div>
                        @endif
                    @empty
                        <!-- Empty State for Training Materials -->
                        <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class='bx bx-file-blank text-gray-400 text-4xl'></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Training Materials Yet</h3>
                            <p class="text-gray-600 mb-6">Get started by creating your first training material for this catalog.</p>
                            <a href="{{ route('training.material.create', $catalog) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md inline-flex items-center transition-colors font-medium">
                                <i class='bx bx-plus mr-2'></i>
                                Create Your First Material
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Metadata Section -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-center text-sm text-gray-600">
                        <span>Created {{ $catalog->created_at->format('F j, Y \a\t g:i A') }}</span>
                        @if($catalog->updated_at != $catalog->created_at)
                            <span>Last updated {{ $catalog->updated_at->format('F j, Y \a\t g:i A') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Hover effects for material cards */
        .hover\:shadow-md:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</x-app-layout>