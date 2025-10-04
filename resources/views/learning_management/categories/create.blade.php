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
                            <a href="{{ route('learning.assessment') }}" class="text-blue-600 hover:text-blue-700">
                                <i class='bx bx-book-open mr-1'></i>
                                Assessment Center
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <span class="ml-1 text-gray-900 font-medium">Create Assessment Category</span>
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

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <i class='bx bx-error-circle mr-2'></i>
                            </div>
                            <div>
                                <p class="font-bold">Please correct the following errors:</p>
                                <ul class="text-sm mt-2 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Create Assessment Category</h1>
                        <p class="text-gray-600 mt-2">Create a new category to organize your assessments</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('learning.assessment') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Back to Assessments
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('learning.assessment.categories.store') }}" class="space-y-8" id="assessmentCategoryForm">
                    @csrf

                    <!-- Category Details Section -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Category Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category Name -->
                            <div class="md:col-span-2">
                                <label for="category_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="category_name" name="category_name" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_name') border-red-500 @enderror"
                                       placeholder="Enter category name (e.g., Technical Skills, Soft Skills)"
                                       value="{{ old('category_name') }}" required>
                                @error('category_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category Slug -->
                            <div>
                                <label for="category_slug" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category Slug <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="category_slug" name="category_slug" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_slug') border-red-500 @enderror"
                                       placeholder="technical-skills"
                                       value="{{ old('category_slug') }}" required readonly>
                                <p class="text-sm text-gray-500 mt-1">Auto-generated from category name. Used in URLs.</p>
                                @error('category_slug')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category Icon -->
                            <div>
                                <label for="category_icon" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category Icon <span class="text-red-500">*</span>
                                </label>
                                <select id="category_icon" name="category_icon" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_icon') border-red-500 @enderror" required>
                                    <option value="">Select an icon</option>
                                    <option value="bx-code-alt" {{ old('category_icon') == 'bx-code-alt' ? 'selected' : '' }}>💻 Code (Technical)</option>
                                    <option value="bx-user-voice" {{ old('category_icon') == 'bx-user-voice' ? 'selected' : '' }}>🗣️ Communication (Soft Skills)</option>
                                    <option value="bx-briefcase" {{ old('category_icon') == 'bx-briefcase' ? 'selected' : '' }}>💼 Briefcase (Business)</option>
                                    <option value="bx-medal" {{ old('category_icon') == 'bx-medal' ? 'selected' : '' }}>🏅 Medal (Certifications)</option>
                                    <option value="bx-brain" {{ old('category_icon') == 'bx-brain' ? 'selected' : '' }}>🧠 Brain (Cognitive)</option>
                                    <option value="bx-heart" {{ old('category_icon') == 'bx-heart' ? 'selected' : '' }}>❤️ Heart (Personal)</option>
                                </select>
                                @error('category_icon')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Category Description -->
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                      placeholder="Enter a detailed description of this assessment category..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Appearance & Settings Section -->
                    <div class="bg-green-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Appearance & Settings</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Color Theme -->
                            <div>
                                <label for="color_theme" class="block text-sm font-medium text-gray-700 mb-2">
                                    Color Theme <span class="text-red-500">*</span>
                                </label>
                                <select id="color_theme" name="color_theme" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('color_theme') border-red-500 @enderror" required>
                                    <option value="">Select a color theme</option>
                                    <option value="blue" {{ old('color_theme') == 'blue' ? 'selected' : '' }}>🔵 Blue (Professional)</option>
                                    <option value="green" {{ old('color_theme') == 'green' ? 'selected' : '' }}>🟢 Green (Growth)</option>
                                    <option value="red" {{ old('color_theme') == 'red' ? 'selected' : '' }}>🔴 Red (Important)</option>
                                    <option value="purple" {{ old('color_theme') == 'purple' ? 'selected' : '' }}>🟣 Purple (Creative)</option>
                                    <option value="orange" {{ old('color_theme') == 'orange' ? 'selected' : '' }}>🟠 Orange (Energetic)</option>
                                    <option value="teal" {{ old('color_theme') == 'teal' ? 'selected' : '' }}>🔷 Teal (Modern)</option>
                                    <option value="indigo" {{ old('color_theme') == 'indigo' ? 'selected' : '' }}>🔵 Indigo (Premium)</option>
                                    <option value="pink" {{ old('color_theme') == 'pink' ? 'selected' : '' }}>🩷 Pink (Friendly)</option>
                                </select>
                                @error('color_theme')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status
                                </label>
                                <select id="is_active" name="is_active" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('is_active') border-red-500 @enderror">
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>✅ Active</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>❌ Inactive</option>
                                </select>
                                <p class="text-sm text-gray-500 mt-1">Only active categories will be visible to users.</p>
                                @error('is_active')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="bg-yellow-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Preview</h2>
                        <p class="text-gray-600 mb-4">This is how your category will appear on the assessment page:</p>
                        
                        <div id="category-preview" class="max-w-sm">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 cursor-pointer hover:shadow-lg transition-all duration-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i id="preview-icon" class='bx bx-code-alt text-white text-2xl'></i>
                                    </div>
                                    <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">0 Assessments</span>
                                </div>
                                <h3 id="preview-title" class="text-lg font-semibold text-gray-900 mb-2">Your Category Name</h3>
                                <p id="preview-description" class="text-gray-600 text-sm mb-4">Your category description will appear here...</p>
                                <div class="flex items-center text-blue-600 text-sm font-medium">
                                    <span>View Assessments</span>
                                    <i class='bx bx-right-arrow-alt ml-1'></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('learning.assessment') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-md font-medium transition-colors">
                            Cancel
                        </a>
                        <button type="submit" name="action" value="draft"
                                class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                            Save as Draft
                        </button>
                        <button type="submit" name="action" value="active"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                            Create & Activate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-generate slug from category name
            const categoryNameInput = document.getElementById('category_name');
            const categorySlugInput = document.getElementById('category_slug');
            
            categoryNameInput.addEventListener('input', function() {
                const name = this.value;
                const slug = name.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '') // Remove invalid characters
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
                    .trim('-'); // Remove leading/trailing hyphens
                
                categorySlugInput.value = slug;
                updatePreview();
            });

            // Update preview when inputs change
            function updatePreview() {
                const name = document.getElementById('category_name').value || 'Your Category Name';
                const description = document.getElementById('description').value || 'Your category description will appear here...';
                const icon = document.getElementById('category_icon').value || 'bx-code-alt';
                const colorTheme = document.getElementById('color_theme').value || 'blue';
                
                // Update preview content
                document.getElementById('preview-title').textContent = name;
                document.getElementById('preview-description').textContent = description;
                document.getElementById('preview-icon').className = `bx ${icon} text-white text-2xl`;
                
                // Update preview colors
                const previewCard = document.getElementById('category-preview').firstElementChild;
                const colorClasses = {
                    blue: 'from-blue-50 to-blue-100 border-blue-200',
                    green: 'from-green-50 to-green-100 border-green-200',
                    red: 'from-red-50 to-red-100 border-red-200',
                    purple: 'from-purple-50 to-purple-100 border-purple-200',
                    orange: 'from-orange-50 to-orange-100 border-orange-200',
                    teal: 'from-teal-50 to-teal-100 border-teal-200',
                    indigo: 'from-indigo-50 to-indigo-100 border-indigo-200',
                    pink: 'from-pink-50 to-pink-100 border-pink-200'
                };
                
                // Remove all color classes and add the selected one
                previewCard.className = `bg-gradient-to-br ${colorClasses[colorTheme]} rounded-lg p-6 cursor-pointer hover:shadow-lg transition-all duration-200`;
                
                // Update icon background and badge colors
                const iconBg = previewCard.querySelector('.w-12');
                const badge = previewCard.querySelector('.text-xs');
                const viewText = previewCard.querySelector('.text-sm.font-medium');
                
                iconBg.className = `w-12 h-12 bg-${colorTheme}-500 rounded-lg flex items-center justify-center`;
                badge.className = `bg-${colorTheme}-500 text-white text-xs px-2 py-1 rounded-full`;
                viewText.className = `flex items-center text-${colorTheme}-600 text-sm font-medium`;
            }

            // Add event listeners for preview updates
            document.getElementById('category_name').addEventListener('input', updatePreview);
            document.getElementById('description').addEventListener('input', updatePreview);
            document.getElementById('category_icon').addEventListener('change', updatePreview);
            document.getElementById('color_theme').addEventListener('change', updatePreview);

            // Form validation and submission
            const form = document.getElementById('assessmentCategoryForm');
            let clickedAction = 'active'; // default action
            
            // Track which submit button was clicked
            form.addEventListener('click', function(e) {
                if (e.target.type === 'submit' && e.target.name === 'action') {
                    clickedAction = e.target.value;
                }
            });
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Clear previous errors
                document.querySelectorAll('.error-message').forEach(el => el.remove());
                document.querySelectorAll('.border-red-500').forEach(el => {
                    el.classList.remove('border-red-500');
                    el.classList.add('border-gray-300');
                });

                // Create form data manually and include the action
                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('category_name', document.getElementById('category_name').value);
                formData.append('category_slug', document.getElementById('category_slug').value);
                formData.append('category_icon', document.getElementById('category_icon').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('color_theme', document.getElementById('color_theme').value);
                formData.append('is_active', document.getElementById('is_active').value);
                formData.append('action', clickedAction); // Include the clicked action

                const submitButton = e.submitter || form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;

                try {
                    // Disable submit button
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Creating...';

                    const response = await fetch('{{ route("learning.assessment.categories.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Show success message
                        showMessage('success', data.message);
                        
                        // Redirect to assessment center (dynamic version) after short delay
                        setTimeout(() => {
                            window.location.href = '{{ route("learning.assessment") }}';
                        }, 1500);
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const fieldElement = document.getElementById(field);
                                if (fieldElement) {
                                    fieldElement.classList.remove('border-gray-300');
                                    fieldElement.classList.add('border-red-500');
                                    
                                    // Add error message
                                    const errorDiv = document.createElement('div');
                                    errorDiv.className = 'error-message text-red-500 text-sm mt-1';
                                    errorDiv.textContent = data.errors[field][0];
                                    fieldElement.parentNode.appendChild(errorDiv);
                                }
                            });
                        }
                        
                        showMessage('error', data.message || 'Please check the form for errors.');
                    }

                } catch (error) {
                    console.error('Form submission error:', error);
                    showMessage('error', 'An unexpected error occurred. Please try again.');
                }

                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });

            // Helper function to show messages
            function showMessage(type, message) {
                const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                const iconClass = type === 'success' ? 'bx-check-circle' : 'bx-error-circle';
                
                const alertDiv = document.createElement('div');
                alertDiv.className = `${alertClass} px-4 py-3 rounded mb-6 border`;
                alertDiv.innerHTML = `
                    <div class="flex">
                        <div class="py-1">
                            <i class='bx ${iconClass} mr-2'></i>
                        </div>
                        <div>
                            <p class="font-bold">${type === 'success' ? 'Success!' : 'Error!'}</p>
                            <p class="text-sm">${message}</p>
                        </div>
                    </div>
                `;
                
                // Insert at the top of the form container
                const container = document.querySelector('.bg-white.shadow.rounded-lg.p-6');
                const breadcrumb = container.querySelector('nav');
                container.insertBefore(alertDiv, breadcrumb.nextSibling);
                
                // Auto-remove success messages
                if (type === 'success') {
                    setTimeout(() => alertDiv.remove(), 3000);
                }
            }
        });
    </script>

    <style>
        /* Enhanced form styling */
        .form-section {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        
        /* Preview card hover effect */
        #category-preview .cursor-pointer:hover {
            transform: translateY(-2px);
        }
        
        /* Custom select styling */
        select option {
            padding: 8px;
        }
        
        /* Focus states */
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</x-app-layout>