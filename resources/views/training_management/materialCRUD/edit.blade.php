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
                                <a href="{{ route('training.materials.show', [$catalog, $material]) }}" class="ml-1 text-gray-500 hover:text-gray-700">
                                    {{ $material->lesson_title }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <span class="ml-1 text-gray-900 font-medium">Edit</span>
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
                        <h1 class="text-3xl font-bold text-gray-900">Edit Training Material</h1>
                        <p class="text-gray-600 mt-2">Update lesson content for <strong>{{ $catalog->title }}</strong></p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('training.materials.show', [$catalog, $material]) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Back to Material
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('training.materials.update', [$catalog, $material]) }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information Section -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Lesson Title -->
                            <div class="md:col-span-2">
                                <label for="lesson_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Lesson Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="lesson_title" name="lesson_title" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lesson_title') border-red-500 @enderror"
                                       placeholder="Enter a compelling lesson title"
                                       value="{{ old('lesson_title', $material->lesson_title) }}">
                            </div>

                            <!-- Competency Selection -->
                            <div class="md:col-span-2">
                                <label for="competency_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Competency <span class="text-red-500">*</span>
                                </label>
                                <select id="competency_id" name="competency_id" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('competency_id') border-red-500 @enderror">
                                    <option value="">Select a competency</option>
                                    @foreach($competencies as $competency)
                                        <option value="{{ $competency->id }}" 
                                                data-levels="{{ $competency->proficiency_levels }}"
                                                {{ (old('competency_id', $material->competency_id) == $competency->id) ? 'selected' : '' }}>
                                            {{ $competency->competency_name }}
                                            @if($competency->framework)
                                                ({{ $competency->framework->framework_name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Proficiency Level -->
                            <div>
                                <label for="proficiency_level" class="block text-sm font-medium text-gray-700 mb-2">
                                    Proficiency Level <span class="text-red-500">*</span>
                                </label>
                                <select id="proficiency_level" name="proficiency_level" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('proficiency_level') border-red-500 @enderror">
                                    <option value="">Select competency first</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Lesson Content Section -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Lesson Content</h2>
                        <p class="text-gray-600 mb-6">Update your lesson content with rich text formatting and images</p>
                        
                        <!-- Rich Text Editor Toolbar -->
                        <div class="bg-white border border-gray-300 rounded-t-md p-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <!-- Text Formatting -->
                                <div class="flex items-center border-r border-gray-300 pr-2 mr-2">
                                    <button type="button" class="editor-btn p-2 hover:bg-gray-100 rounded" data-command="bold" title="Bold">
                                        <i class='bx bx-bold text-lg'></i>
                                    </button>
                                    <button type="button" class="editor-btn p-2 hover:bg-gray-100 rounded" data-command="italic" title="Italic">
                                        <i class='bx bx-italic text-lg'></i>
                                    </button>
                                    <button type="button" class="editor-btn p-2 hover:bg-gray-100 rounded" data-command="underline" title="Underline">
                                        <i class='bx bx-underline text-lg'></i>
                                    </button>
                                </div>

                                <!-- Lists -->
                                <div class="flex items-center border-r border-gray-300 pr-2 mr-2">
                                    <button type="button" class="editor-btn p-2 hover:bg-gray-100 rounded" data-command="insertUnorderedList" title="Bullet List">
                                        <i class='bx bx-list-ul text-lg'></i>
                                    </button>
                                    <button type="button" class="editor-btn p-2 hover:bg-gray-100 rounded" data-command="insertOrderedList" title="Numbered List">
                                        <i class='bx bx-list-ol text-lg'></i>
                                    </button>
                                </div>

                                <!-- Alignment -->
                                <div class="flex items-center border-r border-gray-300 pr-2 mr-2">
                                    <button type="button" class="editor-btn p-2 hover:bg-gray-100 rounded" data-command="justifyLeft" title="Align Left">
                                        <i class='bx bx-align-left text-lg'></i>
                                    </button>
                                    <button type="button" class="editor-btn p-2 hover:bg-gray-100 rounded" data-command="justifyCenter" title="Align Center">
                                        <i class='bx bx-align-middle text-lg'></i>
                                    </button>
                                    <button type="button" class="editor-btn p-2 hover:bg-gray-100 rounded" data-command="justifyRight" title="Align Right">
                                        <i class='bx bx-align-right text-lg'></i>
                                    </button>
                                </div>

                                <!-- Headings -->
                                <div class="flex items-center border-r border-gray-300 pr-2 mr-2">
                                    <select class="heading-select px-2 py-1 border border-gray-300 rounded text-sm">
                                        <option value="">Normal</option>
                                        <option value="h1">Heading 1</option>
                                        <option value="h2">Heading 2</option>
                                        <option value="h3">Heading 3</option>
                                    </select>
                                </div>

                                <!-- Image Upload -->
                                <div class="flex items-center">
                                    <button type="button" class="image-btn p-2 hover:bg-gray-100 rounded" title="Insert Image">
                                        <i class='bx bx-image text-lg'></i>
                                    </button>
                                    <input type="file" id="image-upload" accept="image/*" class="hidden">
                                </div>
                            </div>
                        </div>

                        <!-- Rich Text Editor -->
                        <div class="relative">
                            <div id="lesson-editor" 
                                 class="min-h-[400px] p-4 border-l border-r border-b border-gray-300 rounded-b-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('lesson_content') border-red-500 @enderror" 
                                 contenteditable="true"
                                 data-placeholder="Start writing your lesson content here... You can format text, add images, and create lists.">{!! old('lesson_content', $material->lesson_content) !!}</div>
                            <textarea id="lesson_content" name="lesson_content" class="hidden"></textarea>
                        </div>

                        <!-- Character Count -->
                        <div class="flex justify-between items-center mt-2 text-sm text-gray-500">
                            <span>Use the toolbar above to format your content and insert images</span>
                            <span id="char-count">0 characters</span>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('training.materials.show', [$catalog, $material]) }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-md font-medium transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                            Update Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Rich Text Editor Styles */
        #lesson-editor {
            outline: none;
            line-height: 1.6;
        }
        
        #lesson-editor[data-placeholder-active]:empty::before {
            content: attr(data-placeholder);
            color: #9ca3af;
            font-style: italic;
        }
        
        #lesson-editor h1 {
            font-size: 2em;
            font-weight: bold;
            margin: 0.67em 0;
        }
        
        #lesson-editor h2 {
            font-size: 1.5em;
            font-weight: bold;
            margin: 0.83em 0;
        }
        
        #lesson-editor h3 {
            font-size: 1.17em;
            font-weight: bold;
            margin: 1em 0;
        }
        
        #lesson-editor ul, #lesson-editor ol {
            margin: 1em 0;
            padding-left: 2em;
        }
        
        #lesson-editor li {
            margin: 0.5em 0;
        }
        
        #lesson-editor p {
            margin: 1em 0;
        }
        
        #lesson-editor img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin: 10px 0;
        }
        
        .editor-btn:hover,
        .image-btn:hover {
            background-color: #f3f4f6;
        }
        
        .editor-btn:active,
        .image-btn:active {
            background-color: #e5e7eb;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Competency and Proficiency Level Selection
            const competencySelect = document.getElementById('competency_id');
            const proficiencySelect = document.getElementById('proficiency_level');
            const oldProficiencyLevel = "{{ old('proficiency_level', $material->proficiency_level) }}";

            function updateProficiencyLevels() {
                const selectedOption = competencySelect.options[competencySelect.selectedIndex];
                const levels = selectedOption.getAttribute('data-levels');
                
                // Clear proficiency level options
                proficiencySelect.innerHTML = '<option value="">Select proficiency level</option>';
                
                if (levels && levels > 0) {
                    proficiencySelect.disabled = false;
                    
                    // Add level options based on the competency
                    for (let i = 1; i <= parseInt(levels); i++) {
                        const option = document.createElement('option');
                        option.value = i;
                        
                        // Create meaningful level names
                        let levelName = '';
                        switch(i) {
                            case 1: levelName = 'Beginner'; break;
                            case 2: levelName = 'Intermediate'; break;
                            case 3: levelName = 'Expert'; break;
                            default: levelName = `Level ${i}`;
                        }
                        
                        option.textContent = `Level ${i} - ${levelName}`;
                        
                        // Select the old value if it matches
                        if (oldProficiencyLevel && oldProficiencyLevel == i) {
                            option.selected = true;
                        }
                        
                        proficiencySelect.appendChild(option);
                    }
                } else {
                    proficiencySelect.disabled = true;
                }
            }

            competencySelect.addEventListener('change', updateProficiencyLevels);
            
            // Initialize proficiency levels if competency is already selected
            if (competencySelect.value) {
                updateProficiencyLevels();
            }

            // Rich Text Editor Functionality
            const editor = document.getElementById('lesson-editor');
            const contentInput = document.getElementById('lesson_content');
            const charCount = document.getElementById('char-count');

            // Editor toolbar functionality
            document.querySelectorAll('.editor-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const command = this.getAttribute('data-command');
                    document.execCommand(command, false, null);
                    editor.focus();
                });
            });

            // Heading selection
            document.querySelector('.heading-select').addEventListener('change', function() {
                if (this.value) {
                    document.execCommand('formatBlock', false, this.value);
                    editor.focus();
                }
            });

            // Image upload functionality
            const imageBtn = document.querySelector('.image-btn');
            const imageUpload = document.getElementById('image-upload');

            imageBtn.addEventListener('click', function() {
                imageUpload.click();
            });

            imageUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100%';
                        img.style.height = 'auto';
                        img.style.margin = '10px 0';
                        img.style.borderRadius = '4px';
                        
                        // Insert image at cursor position
                        const selection = window.getSelection();
                        if (selection.rangeCount > 0) {
                            const range = selection.getRangeAt(0);
                            range.insertNode(img);
                            range.collapse(false);
                        } else {
                            editor.appendChild(img);
                        }
                        
                        updateContent();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Update hidden textarea with editor content
            function updateContent() {
                contentInput.value = editor.innerHTML;
                const textContent = editor.textContent || editor.innerText || '';
                charCount.textContent = `${textContent.length} characters`;
            }

            // Update content on editor changes
            editor.addEventListener('input', updateContent);
            editor.addEventListener('paste', function() {
                setTimeout(updateContent, 100);
            });

            // Placeholder functionality
            editor.addEventListener('focus', function() {
                if (this.textContent.trim() === '' && this.innerHTML.trim() === '') {
                    this.setAttribute('data-placeholder-active', 'true');
                }
            });

            editor.addEventListener('blur', function() {
                this.removeAttribute('data-placeholder-active');
            });

            // Initialize content and character count
            updateContent();

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                // Update content before submission
                updateContent();
                
                // Basic client-side validation
                const title = document.getElementById('lesson_title').value;
                const competency = document.getElementById('competency_id').value;
                const proficiencyLevel = document.getElementById('proficiency_level').value;
                const lessonContent = document.getElementById('lesson_content').value;

                if (!title || !competency || !proficiencyLevel || !lessonContent) {
                    e.preventDefault();
                    alert('Please fill in all required fields including lesson content.');
                    return;
                }

                if (lessonContent.trim() === '' || editor.textContent.trim() === '') {
                    e.preventDefault();
                    alert('Please add some content to your lesson.');
                    return;
                }

                // Form will submit normally to backend
            });
        });
    </script>
</x-app-layout>