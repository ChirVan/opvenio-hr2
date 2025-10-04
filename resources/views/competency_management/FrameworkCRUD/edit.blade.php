<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="mx-auto px-4">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Edit Framework</h1>
                        <p class="text-gray-600 mt-1">Update the competency framework details</p>
                    </div>
                    <a href="{{ route('competency.frameworks') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Frameworks
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <form action="{{ route('competency.frameworks.update', $framework) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                        <h3 class="text-lg font-medium text-blue-800">Framework Information</h3>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Framework Name -->
                        <div>
                            <label for="framework_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Framework Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="framework_name" 
                                name="framework_name" 
                                value="{{ old('framework_name', $framework->framework_name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('framework_name') border-red-500 @enderror"
                                placeholder="Enter framework name"
                                required
                            >
                            @error('framework_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                placeholder="Enter framework description"
                                required
                            >{{ old('description', $framework->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Two Column Layout -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Effective Date -->
                            <div>
                                <label for="effective_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Effective Date <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="effective_date" 
                                    name="effective_date" 
                                    value="{{ old('effective_date', $framework->effective_date->format('Y-m-d')) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('effective_date') border-red-500 @enderror"
                                    required
                                >
                                @error('effective_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="status" 
                                    name="status" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status', $framework->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $framework->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="draft" {{ old('status', $framework->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="archived" {{ old('status', $framework->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- End Date (Optional) -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                End Date (Optional)
                            </label>
                            <input 
                                type="date" 
                                id="end_date" 
                                name="end_date" 
                                value="{{ old('end_date', $framework->end_date ? $framework->end_date->format('Y-m-d') : '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror"
                            >
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Leave blank if framework has no end date</p>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes
                            </label>
                            <textarea 
                                id="notes" 
                                name="notes" 
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                                placeholder="Any additional notes or comments about this framework"
                            >{{ old('notes', $framework->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span class="text-red-500">*</span> Required fields
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('competency.frameworks') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md transition-colors duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Framework
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>