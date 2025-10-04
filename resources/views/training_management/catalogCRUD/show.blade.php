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
                    <h2 class="text-2xl font-semibold text-gray-900">Training Catalog Details</h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('training.catalog.edit', $trainingCatalog) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-edit mr-2'></i>
                            Edit
                        </a>
                        <a href="{{ route('training.catalog.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Back to Catalog
                        </a>
                    </div>
                </div>

                <!-- Training Catalog Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Training Catalog Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Title (Framework)</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $trainingCatalog->title }}</p>
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $trainingCatalog->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $trainingCatalog->status_text }}
                            </span>
                        </div>
                        
                        <!-- Label -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Label</label>
                            <p class="text-gray-900">{{ $trainingCatalog->label }}</p>
                        </div>
                        
                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                            <p class="text-gray-900 leading-relaxed">{{ $trainingCatalog->description }}</p>
                        </div>
                        
                        <!-- Related Framework -->
                        @if($trainingCatalog->framework)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Related Framework</label>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $trainingCatalog->framework->framework_name }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $trainingCatalog->framework->description }}</span>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Timestamps -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created</label>
                            <p class="text-sm text-gray-900">{{ $trainingCatalog->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                            <p class="text-sm text-gray-900">{{ $trainingCatalog->updated_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                    <form method="POST" action="{{ route('training.catalog.destroy', $trainingCatalog) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this training catalog entry? This action cannot be undone.')" 
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md inline-flex items-center">
                            <i class='bx bx-trash mr-2'></i>
                            Delete Entry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>