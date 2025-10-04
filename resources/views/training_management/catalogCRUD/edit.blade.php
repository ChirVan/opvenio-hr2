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
                    <h2 class="text-2xl font-semibold text-gray-900">Edit Training Catalog Entry</h2>
                    <a href="{{ route('training.catalog.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                        <i class='bx bx-arrow-back mr-2'></i>
                        Back to Catalog
                    </a>
                </div>

                <!-- Edit Training Catalog Form -->
                <form action="{{ route('training.catalog.update', $trainingCatalog) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Training Catalog Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Training Catalog Information</h3>
                        
                        <div class="space-y-4">
                            <!-- Title (Framework Dropdown) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title (Framework) *</label>
                                <select name="title" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                                    <option value="">Select Framework</option>
                                    @foreach($frameworks as $framework)
                                        @if($framework->status === 'active')
                                            <option value="{{ $framework->framework_name }}" 
                                                {{ (old('title', $trainingCatalog->title) == $framework->framework_name) ? 'selected' : '' }}>
                                                {{ $framework->framework_name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Label -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Label *</label>
                                <input type="text" name="label" value="{{ old('label', $trainingCatalog->label) }}" required
                                    placeholder="Enter a short label or subtitle for the catalog"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('label') border-red-500 @enderror">
                                @error('label')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                                <textarea name="description" rows="4" required
                                    placeholder="Describe the training catalog and what types of training materials it contains..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $trainingCatalog->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" 
                                    {{ old('is_active', $trainingCatalog->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Make this training catalog active and visible</span>
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('training.catalog.index') }}" 
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 inline-flex items-center">
                            <i class='bx bx-x mr-2'></i>
                            Cancel
                        </a>
                        <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md inline-flex items-center">
                            <i class='bx bx-save mr-2'></i>
                            Update Catalog Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>