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
                        <h1 class="text-3xl font-bold text-gray-900">Assessment Center</h1>
                        <p class="text-gray-600 mt-2">Evaluate skills and knowledge through comprehensive assessments</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('learning.assessment.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-plus mr-2'></i>
                            Create Assessment Category
                        </a>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb Navigation -->
            <nav class="flex mb-6" aria-label="Breadcrumb" id="breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <button onclick="showCategories()" class="text-blue-600 hover:text-blue-800 font-medium">
                            Assessment Categories
                        </button>
                    </li>
                </ol>
            </nav>

            <!-- Main Content Container -->
            <div class="bg-white shadow rounded-lg p-6">
                
                <!-- Assessment Categories View -->
                <div id="categoriesView">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Assessment Categories</h2>
                        <p class="text-gray-600">Select a category to view available assessments</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($categories as $category)
                            <div class="bg-gradient-to-br {{ $category->color_classes['gradient'] }} {{ $category->color_classes['border'] }} rounded-lg p-6 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 relative group">
                                <!-- Action Buttons - Show on Hover -->
                                <div class="absolute top-3 right-3 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <button onclick="editCategory({{ $category->id }}, event)" 
                                            class="bg-white hover:bg-gray-50 text-gray-600 hover:text-blue-600 p-2 rounded-full shadow-md border border-gray-200 transition-colors duration-200"
                                            title="Edit Category">
                                        <i class='bx bx-edit text-sm'></i>
                                    </button>
                                    <button onclick="deleteCategory({{ $category->id }}, '{{ $category->category_name }}', event)" 
                                            class="bg-white hover:bg-gray-50 text-gray-600 hover:text-red-600 p-2 rounded-full shadow-md border border-gray-200 transition-colors duration-200"
                                            title="Delete Category">
                                        <i class='bx bx-trash text-sm'></i>
                                    </button>
                                </div>

                                <!-- Category Content - Clickable Area -->
                                <div class="cursor-pointer" onclick="showExaminations('{{ $category->category_slug }}')">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 {{ $category->color_classes['bg'] }} rounded-lg flex items-center justify-center">
                                            <i class='bx {{ $category->category_icon }} text-white text-2xl'></i>
                                        </div>
                                        <span class="{{ $category->color_classes['bg'] }} text-white text-xs px-2 py-1 rounded-full">{{ $category->assessments_count }} Assessments</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category->category_name }}</h3>
                                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($category->description, 80) }}</p>
                                    <div class="flex items-center {{ $category->color_classes['text'] }} text-sm font-medium">
                                        <span>View Assessments</span>
                                        <i class='bx bx-right-arrow-alt ml-1'></i>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <i class='bx bx-folder-open text-gray-300 text-6xl mb-4'></i>
                                <h3 class="text-lg font-semibold text-gray-600 mb-2">No Assessment Categories Found</h3>
                                <p class="text-gray-500 mb-6">Get started by creating your first assessment category.</p>
                                <a href="{{ route('learning.assessment.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md inline-flex items-center">
                                    <i class='bx bx-plus mr-2'></i>
                                    Create First Category
                                </a>
                            </div>
                        @endforelse
                    </div>

                    @if($categories && $categories->hasPages())
                        <div class="mt-6">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>

                <!-- Examinations View -->
                <div id="examinationsView" class="hidden">
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-2" id="examinationsTitle">Technical Skills Assessments</h2>
                                <p class="text-gray-600">Choose an assessment to begin the evaluation</p>
                            </div>
                            <div>
                                <button onclick="createAssessmentForCategory()" id="createAssessmentButton" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                    <i class='bx bx-plus mr-2'></i>
                                    Create Assessment
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="examinationsList">
                        <!-- Examinations will be populated here -->
                    </div>
                </div>

                <!-- Questions View -->
                <div id="questionsView" class="hidden">
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-2" id="questionsTitle">JavaScript Fundamentals Assessment</h2>
                                <p class="text-gray-600">Answer all questions to complete the assessment</p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Progress</div>
                                <div class="text-lg font-semibold text-blue-600">1 of 5</div>
                            </div>
                        </div>
                    </div>

                    <!-- Question Card -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="mb-4">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Question 1</span>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">What is the correct way to declare a variable in JavaScript?</h3>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-white transition-colors">
                                <input type="radio" name="question1" value="a" class="mr-3">
                                <span>var myVariable;</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-white transition-colors">
                                <input type="radio" name="question1" value="b" class="mr-3">
                                <span>let myVariable;</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-white transition-colors">
                                <input type="radio" name="question1" value="c" class="mr-3">
                                <span>const myVariable;</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-white transition-colors">
                                <input type="radio" name="question1" value="d" class="mr-3">
                                <span>All of the above</span>
                            </label>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between">
                        <button class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-md font-medium transition-colors">
                            <i class='bx bx-chevron-left mr-2'></i>
                            Previous
                        </button>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                            Next
                            <i class='bx bx-chevron-right ml-2'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dynamic categories data from server
        const categoriesData = @json($categories->keyBy('category_slug'));
        
        // Track current category for context
        let currentCategory = null;

        function showCategories() {
            document.getElementById('categoriesView').classList.remove('hidden');
            document.getElementById('examinationsView').classList.add('hidden');
            document.getElementById('questionsView').classList.add('hidden');
            
            // Update breadcrumb
            document.getElementById('breadcrumb').innerHTML = `
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <span class="text-gray-700 font-medium">Assessment Categories</span>
                    </li>
                </ol>
            `;
        }

        function showExaminations(categorySlug) {
            document.getElementById('categoriesView').classList.add('hidden');
            document.getElementById('examinationsView').classList.remove('hidden');
            document.getElementById('questionsView').classList.add('hidden');
            
            // Get category data from server
            const category = categoriesData[categorySlug];
            if (!category) {
                console.error('Category not found:', categorySlug);
                return;
            }
            
            // Store current category for context
            currentCategory = category;
            
            document.getElementById('examinationsTitle').textContent = category.category_name + ' Assessments';
            
            // Update breadcrumb
            document.getElementById('breadcrumb').innerHTML = `
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <button onclick="showCategories()" class="text-blue-600 hover:text-blue-800 font-medium">
                            Assessment Categories
                        </button>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class='bx bx-chevron-right text-gray-400'></i>
                            <span class="ml-1 text-gray-700 font-medium">${category.category_name}</span>
                        </div>
                    </li>
                </ol>
            `;
            
            // Fetch quizzes for this category
            const examinationsList = document.getElementById('examinationsList');
            
            // Show loading state
            examinationsList.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class='bx bx-loader-alt animate-spin text-gray-400 text-6xl mb-4'></i>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Loading Assessments...</h3>
                    <p class="text-gray-500">Please wait while we fetch the assessments for this category.</p>
                </div>
            `;
            
            // Fetch quizzes from the server
            fetch(`{{ url('/learning/assessment/categories') }}/${categorySlug}/quizzes`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.quizzes.length > 0) {
                    // Update the assessment count on the category card
                    updateAssessmentCount(categorySlug, data.data.quizzes.length);
                    
                    // Display quizzes
                    const quizzesHtml = data.data.quizzes.map(quiz => `
                        <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 relative group">
                            <!-- Quiz Status Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${quiz.status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                    ${quiz.status === 'published' ? 'Published' : 'Draft'}
                                </span>
                            </div>
                            
                            <!-- Quiz Content -->
                            <div class="cursor-pointer" onclick="startQuiz('${quiz.id}', '${quiz.quiz_title}')">
                                <div class="flex items-center justify-between mb-4 pr-16">
                                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-clipboard text-white text-2xl'></i>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">${quiz.quiz_title}</h3>
                                <p class="text-gray-600 text-sm mb-4">${quiz.description || 'No description available'}</p>
                                
                                <!-- Quiz Details -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Competency:</span>
                                        <span class="text-gray-700 font-medium">${quiz.competency_name}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Questions:</span>
                                        <span class="text-gray-700 font-medium">${quiz.total_questions}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Points:</span>
                                        <span class="text-gray-700 font-medium">${quiz.total_points}</span>
                                    </div>
                                    ${quiz.time_limit ? `
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Time Limit:</span>
                                        <span class="text-gray-700 font-medium">${quiz.time_limit} minutes</span>
                                    </div>
                                    ` : ''}
                                </div>
                                
                                <div class="flex items-center text-blue-600 text-sm font-medium">
                                    <span>Start Assessment</span>
                                    <i class='bx bx-right-arrow-alt ml-1'></i>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    
                    examinationsList.innerHTML = quizzesHtml;
                } else {
                    // Update the assessment count to 0 if no quizzes found
                    updateAssessmentCount(categorySlug, 0);
                    
                    // No quizzes found
                    examinationsList.innerHTML = `
                        <div class="col-span-full text-center py-12">
                            <i class='bx bx-file-blank text-gray-300 text-6xl mb-4'></i>
                            <h3 class="text-lg font-semibold text-gray-600 mb-2">No Assessments Yet</h3>
                            <p class="text-gray-500 mb-6">No assessments have been created for the <strong>${category.category_name}</strong> category.</p>
                            <button onclick="window.location.href='{{ route("learning.quiz") }}?category_id=${category.id}'" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md inline-flex items-center">
                                <i class='bx bx-plus mr-2'></i>
                                Create First Assessment
                            </button>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching quizzes:', error);
                examinationsList.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class='bx bx-error text-red-300 text-6xl mb-4'></i>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Error Loading Assessments</h3>
                        <p class="text-gray-500 mb-6">There was an error loading the assessments. Please try again.</p>
                        <button onclick="showExaminations('${categorySlug}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            <i class='bx bx-refresh mr-2'></i>
                            Retry
                        </button>
                    </div>
                `;
            });
        }

        function startQuiz(quizId, quizTitle) {
            // For now, redirect to the quiz show page
            // In the future, this could start a quiz-taking interface
            window.location.href = `{{ url('/learning/quiz') }}/${quizId}`;
        }

        function createAssessmentForCategory() {
            if (currentCategory) {
                window.location.href = `{{ route('learning.quiz') }}?category_id=${currentCategory.id}`;
            } else {
                window.location.href = `{{ route('learning.quiz') }}`;
            }
        }

        function showQuestions(examId, examTitle) {
            document.getElementById('categoriesView').classList.add('hidden');
            document.getElementById('examinationsView').classList.add('hidden');
            document.getElementById('questionsView').classList.remove('hidden');
            
            document.getElementById('questionsTitle').textContent = examTitle;
            
            // Update breadcrumb
            document.getElementById('breadcrumb').innerHTML = `
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <button onclick="showCategories()" class="text-blue-600 hover:text-blue-800 font-medium">
                            Assessment Categories
                        </button>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class='bx bx-chevron-right text-gray-400'></i>
                            <button onclick="history.back()" class="ml-1 text-blue-600 hover:text-blue-800 font-medium">
                                Assessments
                            </button>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class='bx bx-chevron-right text-gray-400'></i>
                            <span class="ml-1 text-gray-700 font-medium">${examTitle}</span>
                        </div>
                    </li>
                </ol>
            `;
        }

        // Category Management Functions
        function editCategory(categoryId, event) {
            event.stopPropagation(); // Prevent triggering the category click
            
            // Redirect to the edit page
            window.location.href = `{{ url('/learning/assessment/categories') }}/${categoryId}/edit`;
        }

        function deleteCategory(categoryId, categoryName, event) {
            event.stopPropagation(); // Prevent triggering the category click
            
            // Show confirmation dialog
            if (confirm(`Are you sure you want to delete the "${categoryName}" category?\n\nThis action cannot be undone and will remove all assessments in this category.`)) {
                // Show loading state
                const button = event.target.closest('button');
                const originalContent = button.innerHTML;
                button.innerHTML = '<i class="bx bx-loader-alt animate-spin text-sm"></i>';
                button.disabled = true;

                // Send delete request
                fetch(`{{ url('/learning/assessment/categories') }}/${categoryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showMessage('success', data.message);
                        
                        // Remove the category card from the UI
                        const categoryCard = button.closest('.bg-gradient-to-br');
                        categoryCard.style.opacity = '0';
                        categoryCard.style.transform = 'scale(0.95)';
                        
                        setTimeout(() => {
                            categoryCard.remove();
                            
                            // Check if there are no more categories
                            const remainingCategories = document.querySelectorAll('.bg-gradient-to-br').length;
                            if (remainingCategories === 0) {
                                location.reload(); // Reload to show empty state
                            }
                        }, 300);
                    } else {
                        showMessage('error', data.message || 'Failed to delete category.');
                        // Restore button
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    showMessage('error', 'An error occurred while deleting the category.');
                    // Restore button
                    button.innerHTML = originalContent;
                    button.disabled = false;
                });
            }
        }

        // Helper function to update assessment count on category cards
        function updateAssessmentCount(categorySlug, count) {
            // Find the category card by looking for the onclick attribute that contains the category slug
            const categoryCards = document.querySelectorAll('[onclick*="showExaminations"]');
            categoryCards.forEach(card => {
                const onclickAttr = card.getAttribute('onclick');
                if (onclickAttr && onclickAttr.includes(`'${categorySlug}'`)) {
                    // Find the assessment count badge within this card
                    const countBadge = card.querySelector('span[class*="text-white text-xs"]');
                    if (countBadge) {
                        countBadge.textContent = `${count} Assessment${count !== 1 ? 's' : ''}`;
                    }
                    
                    // Update the category data as well
                    if (categoriesData[categorySlug]) {
                        categoriesData[categorySlug].assessments_count = count;
                    }
                }
            });
        }

        // Helper function to show messages (reuse from create form)
        function showMessage(type, message) {
            const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            const iconClass = type === 'success' ? 'bx-check-circle' : 'bx-error-circle';
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `${alertClass} px-4 py-3 rounded mb-6 border fixed top-4 right-4 z-50 shadow-lg max-w-md`;
            alertDiv.innerHTML = `
                <div class="flex">
                    <div class="py-1">
                        <i class='bx ${iconClass} mr-2'></i>
                    </div>
                    <div>
                        <p class="font-bold">${type === 'success' ? 'Success!' : 'Error!'}</p>
                        <p class="text-sm">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-lg font-bold">&times;</button>
                </div>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>

    <style>
        /* Smooth transitions for view changes */
        .hidden {
            display: none !important;
        }
        
        /* Hover effects for cards */
        .cursor-pointer:hover {
            transform: translateY(-2px);
        }
        
        /* Radio button styling */
        input[type="radio"] {
            accent-color: #3b82f6;
        }
        
        /* Progress indicator */
        .progress-bar {
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
        }

        /* Category card enhancements */
        .group:hover .cursor-pointer {
            transform: none; /* Prevent double transform when hovering action buttons */
        }

        /* Action button animations */
        .group:hover {
            transform: translateY(-2px);
        }

        /* Spinning animation for loading states */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Smooth transitions for category removal */
        .bg-gradient-to-br {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
    </style>
</x-app-layout>