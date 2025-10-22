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
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Training Catalog</h2>
                    <a href="{{ route('training.catalog.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                        <i class='bx bx-plus mr-2'></i>
                        Training Catalog
                    </a>
                </div>

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

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <i class='bx bx-error-circle mr-2'></i>
                            </div>
                            <div>
                                <p class="font-bold">Error!</p>
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Info Section -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class='bx bx-info-circle text-blue-400 text-xl'></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Select a competency framework below to view and manage training materials for that framework.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Training Catalog Entries -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="catalog-entries">
                    @forelse($trainingCatalogs as $catalog)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-200 group">
                            <div class="p-6 cursor-pointer" onclick="window.location.href='{{ route('training.catalog.detail', $catalog) }}'">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class='bx bx-book text-blue-600 text-2xl'></i>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $catalog->title }}</h3>
                                            <p class="text-sm text-gray-500">{{ $catalog->label }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $catalog->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $catalog->status_text }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $catalog->description }}</p>
                                
                                @if($catalog->framework)
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $catalog->framework->framework_name }}
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="text-xs text-gray-500">
                                        Created {{ $catalog->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action buttons outside the clickable area -->
                            <div class="px-6 pb-6">
                                <div class="flex space-x-3">
                                    <a href="{{ route('training.catalog.edit', $catalog) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 transition-colors" 
                                       title="Edit">
                                        <i class='bx bx-edit text-lg'></i>
                                    </a>
                                    <form method="POST" action="{{ route('training.catalog.destroy', $catalog) }}" class="inline delete-catalog-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-800 transition-colors delete-catalog-btn" title="Delete">
                                            <i class='bx bx-trash text-lg'></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class='bx bx-book text-gray-400 text-4xl'></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Training Catalogs Found</h3>
                            <p class="text-gray-600 mb-4">There are no training catalog entries yet.</p>
                            <a href="{{ route('training.catalog.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                <i class='bx bx-plus mr-2'></i>
                                Create First Catalog Entry
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        console.log('Script tag loaded - INLINE');
        
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM Content Loaded - INLINE');
            
            const deleteButtons = document.querySelectorAll('.delete-catalog-btn');
            console.log('Found delete buttons - INLINE:', deleteButtons.length);
            
            deleteButtons.forEach(function(btn) {
                console.log('Attaching listener to button - INLINE');
                btn.addEventListener('click', function(e) {
                    console.log('Delete button clicked - INLINE');
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const form = btn.closest('form');
                    
                    if (typeof Swal !== 'undefined') {
                        console.log('Swal is defined - INLINE');
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
                    } else {
                        console.error('Swal is NOT defined - INLINE');
                        alert('SweetAlert2 is not loaded!');
                    }
                });
            });
        });
    </script>
</x-app-layout>