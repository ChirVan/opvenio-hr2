<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6 px-4">
        {{-- Supervisor-only access notice --}}
        @if (!auth()->user()->isSupervisor())
            <div class="mb-6 p-5 bg-amber-50 border-2 border-amber-400 rounded-xl">
                <div class="flex items-start gap-3">
                    <i class='bx bx-lock-alt text-3xl text-amber-600 mt-0.5'></i>
                    <div>
                        <h4 class="font-bold text-amber-800 text-lg">View-Only Mode</h4>
                        <p class="text-amber-700 text-sm mt-1">Only <strong>Supervisors</strong> can submit hands-on evaluations. You are logged in as <strong class="uppercase">{{ auth()->user()->role }}</strong>.</p>
                        <p class="text-amber-600 text-xs mt-2">If you believe you should have supervisor access, contact your system administrator.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('training.evaluation.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <i class='bx bx-arrow-back text-2xl'></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Hands-on Evaluation</h1>
                    <p class="text-gray-600 mt-1">Evaluate employee's practical skills and competencies</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('training.evaluation.submit', $employee->employee_id) }}"
            @if(!auth()->user()->isSupervisor()) onsubmit="return false;" @endif>
            @csrf
            <input type="hidden" name="result_ids" value="{{ $results->pluck('result_id')->implode(',') }}">

        {{-- Server-side integrity / validation errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-2 border-red-400 rounded-xl">
                <h4 class="font-bold text-red-800 flex items-center gap-2 mb-2">
                    <i class='bx bx-shield-x text-xl'></i> Evaluation Integrity Check Failed
                </h4>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Employee Info & Assessments -->
            <div class="lg:col-span-1">
                <!-- Employee Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-indigo-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($employee->employee_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $employee->employee_name }}</h3>
                            <p class="text-sm text-gray-500">ID: {{ $employee->employee_id }}</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Assessments Completed:</span> {{ $results->count() }}
                        </p>
                    </div>
                </div>

                <!-- Completed Assessments -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h4 class="font-bold text-gray-800 mb-4">
                        <i class='bx bx-list-check text-purple-600 mr-2'></i>Completed Assessments
                    </h4>
                    <div class="space-y-3">
                        @foreach($results as $result)
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium text-sm text-gray-800">{{ $result->quiz_title }}</span>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $result->score >= 75 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $result->score }}%
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">{{ $result->category_name }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class='bx bx-calendar mr-1'></i>
                                    {{ \Carbon\Carbon::parse($result->completed_at)->format('M d, Y') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column - Evaluation Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-t-xl p-6 text-white">
                        <h3 class="text-xl font-bold">Performance Competencies Assessment</h3>
                        <p class="text-purple-100 text-sm mt-1">Rate each competency based on demonstrated performance</p>
                    </div>

                    <!-- Rating Legend -->
                    <div class="p-6 bg-gray-50 border-b border-gray-200">
                        <h6 class="font-bold text-gray-700 mb-3">Rating Scale:</h6>
                        <div class="grid grid-cols-5 gap-2 text-center">
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white">Exceptional</span>
                                <p class="text-xs text-gray-500 mt-1">Exceeds expectations</p>
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-teal-500 text-white">Highly Effective</span>
                                <p class="text-xs text-gray-500 mt-1">High standards</p>
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-500 text-white">Proficient</span>
                                <p class="text-xs text-gray-500 mt-1">Meets expectations</p>
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-yellow-500 text-white">Inconsistent</span>
                                <p class="text-xs text-gray-500 mt-1">Sometimes meets</p>
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white">Unsatisfactory</span>
                                <p class="text-xs text-gray-500 mt-1">Below expectations</p>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluation Form -->
                    <div class="p-6">

                        @if($errors->any())
                            <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Soft Skills Section -->
                        <div class="mb-4 px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
                            <h4 class="text-white font-bold text-lg flex items-center gap-2">
                                <i class='bx bx-brain'></i> Soft Skills / Behavioral Competencies
                            </h4>
                            <p class="text-indigo-100 text-sm mt-1">Rate behavioral and cognitive competencies</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Competency 1 -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <label class="block font-medium text-gray-800 mb-2">
                                    1. Skill and proficiency in carrying out assignment
                                </label>
                                <select name="competency_1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Rating</option>
                                    <option value="exceptional" {{ old('competency_1') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                    <option value="highly_effective" {{ old('competency_1') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                    <option value="proficient" {{ old('competency_1') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                    <option value="inconsistent" {{ old('competency_1') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                    <option value="unsatisfactory" {{ old('competency_1') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                </select>
                                <details class="mt-2">
                                    <summary class="text-xs text-indigo-600 cursor-pointer hover:text-indigo-800 font-medium"><i class='bx bx-info-circle mr-1'></i>Evaluation Criteria</summary>
                                    <div class="mt-2 text-xs text-gray-600 space-y-1 bg-white p-3 rounded border border-indigo-100">
                                        <p><span class="font-bold text-green-600">Exceptional:</span> Executes assignments flawlessly and independently; adapts to complex situations</p>
                                        <p><span class="font-bold text-teal-600">Highly Effective:</span> Handles assignments with high skill; rarely requires guidance</p>
                                        <p><span class="font-bold text-blue-600">Proficient:</span> Carries out assignments competently with standard supervision</p>
                                        <p><span class="font-bold text-yellow-600">Inconsistent:</span> Performance varies; sometimes needs additional supervision or correction</p>
                                        <p><span class="font-bold text-red-600">Unsatisfactory:</span> Frequently unable to complete assignments without significant help</p>
                                    </div>
                                </details>
                            </div>

                            <!-- Competency 2 -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <label class="block font-medium text-gray-800 mb-2">
                                    2. Possesses skills and knowledge to perform job effectively
                                </label>
                                <select name="competency_2" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Rating</option>
                                    <option value="exceptional" {{ old('competency_2') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                    <option value="highly_effective" {{ old('competency_2') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                    <option value="proficient" {{ old('competency_2') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                    <option value="inconsistent" {{ old('competency_2') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                    <option value="unsatisfactory" {{ old('competency_2') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                </select>
                                <details class="mt-2">
                                    <summary class="text-xs text-indigo-600 cursor-pointer hover:text-indigo-800 font-medium"><i class='bx bx-info-circle mr-1'></i>Evaluation Criteria</summary>
                                    <div class="mt-2 text-xs text-gray-600 space-y-1 bg-white p-3 rounded border border-indigo-100">
                                        <p><span class="font-bold text-green-600">Exceptional:</span> Deep domain expertise; mentors others and contributes to knowledge sharing</p>
                                        <p><span class="font-bold text-teal-600">Highly Effective:</span> Strong job knowledge; applies skills effectively across varied situations</p>
                                        <p><span class="font-bold text-blue-600">Proficient:</span> Adequate knowledge and skills for current role requirements</p>
                                        <p><span class="font-bold text-yellow-600">Inconsistent:</span> Knowledge gaps in some areas; needs additional training for certain tasks</p>
                                        <p><span class="font-bold text-red-600">Unsatisfactory:</span> Lacks fundamental knowledge/skills needed for the position</p>
                                    </div>
                                </details>
                            </div>

                            <!-- Competency 3 -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <label class="block font-medium text-gray-800 mb-2">
                                    3. Skill at planning, organizing and prioritizing workload
                                </label>
                                <select name="competency_3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Rating</option>
                                    <option value="exceptional" {{ old('competency_3') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                    <option value="highly_effective" {{ old('competency_3') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                    <option value="proficient" {{ old('competency_3') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                    <option value="inconsistent" {{ old('competency_3') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                    <option value="unsatisfactory" {{ old('competency_3') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                </select>
                                <details class="mt-2">
                                    <summary class="text-xs text-indigo-600 cursor-pointer hover:text-indigo-800 font-medium"><i class='bx bx-info-circle mr-1'></i>Evaluation Criteria</summary>
                                    <div class="mt-2 text-xs text-gray-600 space-y-1 bg-white p-3 rounded border border-indigo-100">
                                        <p><span class="font-bold text-green-600">Exceptional:</span> Masterfully plans complex workflows; anticipates issues and reprioritizes proactively</p>
                                        <p><span class="font-bold text-teal-600">Highly Effective:</span> Organizes tasks well; manages competing priorities effectively</p>
                                        <p><span class="font-bold text-blue-600">Proficient:</span> Plans work adequately; meets most deadlines with standard organization</p>
                                        <p><span class="font-bold text-yellow-600">Inconsistent:</span> Sometimes disorganized; misses priorities or deadlines occasionally</p>
                                        <p><span class="font-bold text-red-600">Unsatisfactory:</span> Poor planning; frequently misses deadlines and fails to prioritize</p>
                                    </div>
                                </details>
                            </div>

                            <!-- Competency 4 -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <label class="block font-medium text-gray-800 mb-2">
                                    4. Holds self accountable for assigned responsibilities; sees task through to completion
                                </label>
                                <select name="competency_4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Rating</option>
                                    <option value="exceptional" {{ old('competency_4') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                    <option value="highly_effective" {{ old('competency_4') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                    <option value="proficient" {{ old('competency_4') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                    <option value="inconsistent" {{ old('competency_4') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                    <option value="unsatisfactory" {{ old('competency_4') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                </select>
                                <details class="mt-2">
                                    <summary class="text-xs text-indigo-600 cursor-pointer hover:text-indigo-800 font-medium"><i class='bx bx-info-circle mr-1'></i>Evaluation Criteria</summary>
                                    <div class="mt-2 text-xs text-gray-600 space-y-1 bg-white p-3 rounded border border-indigo-100">
                                        <p><span class="font-bold text-green-600">Exceptional:</span> Takes full ownership; proactively resolves issues and follows through beyond expectations</p>
                                        <p><span class="font-bold text-teal-600">Highly Effective:</span> Reliably accountable; completes responsibilities with minimal follow-up</p>
                                        <p><span class="font-bold text-blue-600">Proficient:</span> Completes assigned tasks responsibly when supervised</p>
                                        <p><span class="font-bold text-yellow-600">Inconsistent:</span> Sometimes avoids responsibility or leaves tasks incomplete</p>
                                        <p><span class="font-bold text-red-600">Unsatisfactory:</span> Avoids accountability; frequently fails to complete or follow through on tasks</p>
                                    </div>
                                </details>
                            </div>

                            <!-- Competency 5 -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <label class="block font-medium text-gray-800 mb-2">
                                    5. Proficiency at improving work methods and procedures for greater efficiency
                                </label>
                                <select name="competency_5" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Rating</option>
                                    <option value="exceptional" {{ old('competency_5') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                    <option value="highly_effective" {{ old('competency_5') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                    <option value="proficient" {{ old('competency_5') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                    <option value="inconsistent" {{ old('competency_5') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                    <option value="unsatisfactory" {{ old('competency_5') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                </select>
                                <details class="mt-2">
                                    <summary class="text-xs text-indigo-600 cursor-pointer hover:text-indigo-800 font-medium"><i class='bx bx-info-circle mr-1'></i>Evaluation Criteria</summary>
                                    <div class="mt-2 text-xs text-gray-600 space-y-1 bg-white p-3 rounded border border-indigo-100">
                                        <p><span class="font-bold text-green-600">Exceptional:</span> Innovates new methods that significantly improve team/department efficiency</p>
                                        <p><span class="font-bold text-teal-600">Highly Effective:</span> Regularly suggests and implements improvements to work processes</p>
                                        <p><span class="font-bold text-blue-600">Proficient:</span> Follows standard procedures and makes minor improvements when guided</p>
                                        <p><span class="font-bold text-yellow-600">Inconsistent:</span> Rarely seeks to improve methods; resistant to some process changes</p>
                                        <p><span class="font-bold text-red-600">Unsatisfactory:</span> No effort to improve work methods; resists change and optimization</p>
                                    </div>
                                </details>
                            </div>
                        </div>

                        <!-- Hard Skills / Physical Performance Section -->
                        <div class="mt-8 mb-4 px-4 py-3 bg-gradient-to-r from-pink-500 to-rose-600 rounded-lg">
                            <h4 class="text-white font-bold text-lg flex items-center gap-2">
                                <i class='bx bx-dumbbell'></i> Hard Skills / Physical Performance
                            </h4>
                            <p class="text-pink-100 text-sm mt-1">Conduct practical tests and rate based on observed results</p>
                        </div>

                        <!-- Score Legend -->
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs font-bold text-gray-700 mb-1">Score → Rating Guide:</p>
                            <div class="grid grid-cols-5 gap-1 text-center">
                                <span class="text-xs px-1 py-0.5 rounded bg-green-100 text-green-700">90-100 Exceptional</span>
                                <span class="text-xs px-1 py-0.5 rounded bg-teal-100 text-teal-700">75-89 Highly Eff.</span>
                                <span class="text-xs px-1 py-0.5 rounded bg-blue-100 text-blue-700">60-74 Proficient</span>
                                <span class="text-xs px-1 py-0.5 rounded bg-yellow-100 text-yellow-700">40-59 Inconsistent</span>
                                <span class="text-xs px-1 py-0.5 rounded bg-red-100 text-red-700">0-39 Unsatisfactory</span>
                            </div>
                        </div>

                        <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-sm text-amber-800"><i class='bx bx-bulb text-amber-600 mr-1'></i><strong>Tip:</strong> For each hard skill below, fill in the <em>Practical Test</em> (left) first. The score will auto-suggest a rating (right). You can override the suggestion.</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Hard Skill 1: Technical Proficiency -->
                            <div class="p-4 bg-pink-50 rounded-lg border border-pink-200">
                                <label class="block font-semibold text-gray-800 mb-3">
                                    6. Technical Proficiency - Demonstrates hands-on technical skills relevant to the role
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Left: Practical Test -->
                                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                                        <h6 class="text-xs font-bold text-pink-600 mb-2 flex items-center gap-1"><i class='bx bx-test-tube'></i> Practical Test</h6>
                                        <p class="text-xs text-gray-500 mb-2">Assign a hands-on task using required tools/equipment. Observe and score.</p>
                                        <div class="flex items-center gap-2 mb-2">
                                            <label class="text-xs text-gray-600 w-14 shrink-0">Score:</label>
                                            <input type="number" name="practical_score_1" min="0" max="100" placeholder="0-100" required
                                                class="w-full px-2 py-1 text-sm border border-pink-300 rounded focus:ring-1 focus:ring-pink-500"
                                                value="{{ old('practical_score_1') }}" data-hard-skill="hard_skill_1" oninput="updateScoreBadge(this, 'badge-test-1')">
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-600 practical-score-badge" id="badge-test-1">--%</span>
                                        </div>
                                        <textarea name="practical_obs_1" rows="2" required placeholder="Describe the task assigned and what you observed..."
                                            class="w-full px-2 py-1 text-xs border border-pink-200 rounded focus:ring-1 focus:ring-pink-500">{{ old('practical_obs_1') }}</textarea>
                                    </div>
                                    <!-- Right: Rating -->
                                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                                        <h6 class="text-xs font-bold text-pink-600 mb-2 flex items-center gap-1"><i class='bx bx-star'></i> Rating</h6>
                                        <div class="mb-2 px-3 py-2 bg-pink-50 rounded flex items-center gap-2" id="ref-hard_skill_1">
                                            <i class='bx bx-bar-chart-alt-2 text-pink-500'></i>
                                            <span class="text-xs text-gray-500">Test Score: </span>
                                            <span class="text-sm font-bold text-gray-800" id="display-score-1">--</span>
                                            <span class="text-xs ml-auto font-medium" id="display-suggest-1">Awaiting score</span>
                                        </div>
                                        <select name="hard_skill_1" id="hard_skill_1" required class="w-full px-3 py-2 border border-pink-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm">
                                            <option value="">Select Rating</option>
                                            <option value="exceptional" {{ old('hard_skill_1') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                            <option value="highly_effective" {{ old('hard_skill_1') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                            <option value="proficient" {{ old('hard_skill_1') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                            <option value="inconsistent" {{ old('hard_skill_1') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                            <option value="unsatisfactory" {{ old('hard_skill_1') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                        </select>
                                        <div class="hidden mt-2 p-2 bg-red-50 border border-red-300 rounded-lg mismatch-warning" id="mismatch-warn-1">
                                            <p class="text-xs text-red-700 font-bold flex items-center gap-1"><i class='bx bx-error-circle'></i> Rating does not match test score!</p>
                                            <p class="text-xs text-red-600 mt-1">You selected a different rating than the test score suggests. Provide a justification (min 50 chars):</p>
                                            <textarea name="override_justification_1" id="override-just-1" rows="2" maxlength="500" placeholder="Explain why you are overriding the suggested rating..."
                                                class="w-full mt-1 px-2 py-1 text-xs border border-red-300 rounded focus:ring-1 focus:ring-red-500">{{ old('override_justification_1') }}</textarea>
                                            <p class="text-xs text-gray-400 mt-1 override-char-count" id="override-chars-1">0 / 50 min characters</p>
                                        </div>
                                        <details class="mt-2">
                                            <summary class="text-xs text-pink-600 cursor-pointer hover:text-pink-800 font-medium"><i class='bx bx-info-circle mr-1'></i>Criteria</summary>
                                            <div class="mt-1 text-xs text-gray-600 space-y-1">
                                                <p><span class="font-bold text-green-600">Exceptional:</span> Operates all tools flawlessly; can troubleshoot & train others</p>
                                                <p><span class="font-bold text-red-600">Unsatisfactory:</span> Cannot operate tools without constant assistance</p>
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            </div>

                            <!-- Hard Skill 2: Physical Performance -->
                            <div class="p-4 bg-pink-50 rounded-lg border border-pink-200">
                                <label class="block font-semibold text-gray-800 mb-3">
                                    7. Physical Performance - Physical ability and stamina to perform job tasks effectively
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                                        <h6 class="text-xs font-bold text-pink-600 mb-2 flex items-center gap-1"><i class='bx bx-test-tube'></i> Practical Test</h6>
                                        <p class="text-xs text-gray-500 mb-2">Observe physical stamina during task execution. Record endurance & effort.</p>
                                        <div class="flex items-center gap-2 mb-2">
                                            <label class="text-xs text-gray-600 w-14 shrink-0">Score:</label>
                                            <input type="number" name="practical_score_2" min="0" max="100" placeholder="0-100" required
                                                class="w-full px-2 py-1 text-sm border border-pink-300 rounded focus:ring-1 focus:ring-pink-500"
                                                value="{{ old('practical_score_2') }}" data-hard-skill="hard_skill_2" oninput="updateScoreBadge(this, 'badge-test-2')">
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-600 practical-score-badge" id="badge-test-2">--%</span>
                                        </div>
                                        <textarea name="practical_obs_2" rows="2" required placeholder="Describe the physical task and employee's endurance/performance..."
                                            class="w-full px-2 py-1 text-xs border border-pink-200 rounded focus:ring-1 focus:ring-pink-500">{{ old('practical_obs_2') }}</textarea>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                                        <h6 class="text-xs font-bold text-pink-600 mb-2 flex items-center gap-1"><i class='bx bx-star'></i> Rating</h6>
                                        <div class="mb-2 px-3 py-2 bg-pink-50 rounded flex items-center gap-2" id="ref-hard_skill_2">
                                            <i class='bx bx-bar-chart-alt-2 text-pink-500'></i>
                                            <span class="text-xs text-gray-500">Test Score: </span>
                                            <span class="text-sm font-bold text-gray-800" id="display-score-2">--</span>
                                            <span class="text-xs ml-auto font-medium" id="display-suggest-2">Awaiting score</span>
                                        </div>
                                        <select name="hard_skill_2" id="hard_skill_2" required class="w-full px-3 py-2 border border-pink-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm">
                                            <option value="">Select Rating</option>
                                            <option value="exceptional" {{ old('hard_skill_2') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                            <option value="highly_effective" {{ old('hard_skill_2') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                            <option value="proficient" {{ old('hard_skill_2') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                            <option value="inconsistent" {{ old('hard_skill_2') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                            <option value="unsatisfactory" {{ old('hard_skill_2') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                        </select>
                                        <div class="hidden mt-2 p-2 bg-red-50 border border-red-300 rounded-lg mismatch-warning" id="mismatch-warn-2">
                                            <p class="text-xs text-red-700 font-bold flex items-center gap-1"><i class='bx bx-error-circle'></i> Rating does not match test score!</p>
                                            <p class="text-xs text-red-600 mt-1">You selected a different rating than the test score suggests. Provide a justification (min 50 chars):</p>
                                            <textarea name="override_justification_2" id="override-just-2" rows="2" maxlength="500" placeholder="Explain why you are overriding the suggested rating..."
                                                class="w-full mt-1 px-2 py-1 text-xs border border-red-300 rounded focus:ring-1 focus:ring-red-500">{{ old('override_justification_2') }}</textarea>
                                            <p class="text-xs text-gray-400 mt-1 override-char-count" id="override-chars-2">0 / 50 min characters</p>
                                        </div>
                                        <details class="mt-2">
                                            <summary class="text-xs text-pink-600 cursor-pointer hover:text-pink-800 font-medium"><i class='bx bx-info-circle mr-1'></i>Criteria</summary>
                                            <div class="mt-1 text-xs text-gray-600 space-y-1">
                                                <p><span class="font-bold text-green-600">Exceptional:</span> Sustains high output throughout shift; exceeds endurance benchmarks</p>
                                                <p><span class="font-bold text-red-600">Unsatisfactory:</span> Cannot meet physical demands; frequent breaks</p>
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            </div>

                            <!-- Hard Skill 3: Output Quality -->
                            <div class="p-4 bg-pink-50 rounded-lg border border-pink-200">
                                <label class="block font-semibold text-gray-800 mb-3">
                                    8. Output Quality - Quality and accuracy of work output and deliverables
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                                        <h6 class="text-xs font-bold text-pink-600 mb-2 flex items-center gap-1"><i class='bx bx-test-tube'></i> Practical Test</h6>
                                        <p class="text-xs text-gray-500 mb-2">Review work samples or deliverables. Check for accuracy, completeness & defects.</p>
                                        <div class="flex items-center gap-2 mb-2">
                                            <label class="text-xs text-gray-600 w-14 shrink-0">Score:</label>
                                            <input type="number" name="practical_score_3" min="0" max="100" placeholder="0-100" required
                                                class="w-full px-2 py-1 text-sm border border-pink-300 rounded focus:ring-1 focus:ring-pink-500"
                                                value="{{ old('practical_score_3') }}" data-hard-skill="hard_skill_3" oninput="updateScoreBadge(this, 'badge-test-3')">
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-600 practical-score-badge" id="badge-test-3">--%</span>
                                        </div>
                                        <textarea name="practical_obs_3" rows="2" required placeholder="Describe the deliverable reviewed and defects found (if any)..."
                                            class="w-full px-2 py-1 text-xs border border-pink-200 rounded focus:ring-1 focus:ring-pink-500">{{ old('practical_obs_3') }}</textarea>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                                        <h6 class="text-xs font-bold text-pink-600 mb-2 flex items-center gap-1"><i class='bx bx-star'></i> Rating</h6>
                                        <div class="mb-2 px-3 py-2 bg-pink-50 rounded flex items-center gap-2" id="ref-hard_skill_3">
                                            <i class='bx bx-bar-chart-alt-2 text-pink-500'></i>
                                            <span class="text-xs text-gray-500">Test Score: </span>
                                            <span class="text-sm font-bold text-gray-800" id="display-score-3">--</span>
                                            <span class="text-xs ml-auto font-medium" id="display-suggest-3">Awaiting score</span>
                                        </div>
                                        <select name="hard_skill_3" id="hard_skill_3" required class="w-full px-3 py-2 border border-pink-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm">
                                            <option value="">Select Rating</option>
                                            <option value="exceptional" {{ old('hard_skill_3') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                            <option value="highly_effective" {{ old('hard_skill_3') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                            <option value="proficient" {{ old('hard_skill_3') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                            <option value="inconsistent" {{ old('hard_skill_3') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                            <option value="unsatisfactory" {{ old('hard_skill_3') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                        </select>
                                        <div class="hidden mt-2 p-2 bg-red-50 border border-red-300 rounded-lg mismatch-warning" id="mismatch-warn-3">
                                            <p class="text-xs text-red-700 font-bold flex items-center gap-1"><i class='bx bx-error-circle'></i> Rating does not match test score!</p>
                                            <p class="text-xs text-red-600 mt-1">You selected a different rating than the test score suggests. Provide a justification (min 50 chars):</p>
                                            <textarea name="override_justification_3" id="override-just-3" rows="2" maxlength="500" placeholder="Explain why you are overriding the suggested rating..."
                                                class="w-full mt-1 px-2 py-1 text-xs border border-red-300 rounded focus:ring-1 focus:ring-red-500">{{ old('override_justification_3') }}</textarea>
                                            <p class="text-xs text-gray-400 mt-1 override-char-count" id="override-chars-3">0 / 50 min characters</p>
                                        </div>
                                        <details class="mt-2">
                                            <summary class="text-xs text-pink-600 cursor-pointer hover:text-pink-800 font-medium"><i class='bx bx-info-circle mr-1'></i>Criteria</summary>
                                            <div class="mt-1 text-xs text-gray-600 space-y-1">
                                                <p><span class="font-bold text-green-600">Exceptional:</span> Zero defects; output exceeds standards & serves as benchmark</p>
                                                <p><span class="font-bold text-red-600">Unsatisfactory:</span> Frequent errors; fails minimum quality standards</p>
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            </div>

                            <!-- Hard Skill 4: Safety Compliance -->
                            <div class="p-4 bg-pink-50 rounded-lg border border-pink-200">
                                <label class="block font-semibold text-gray-800 mb-3">
                                    9. Safety Compliance - Adherence to safety protocols and workplace regulations
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                                        <h6 class="text-xs font-bold text-pink-600 mb-2 flex items-center gap-1"><i class='bx bx-test-tube'></i> Practical Test</h6>
                                        <p class="text-xs text-gray-500 mb-2">Observe safety protocol adherence during task. Note PPE usage & violations.</p>
                                        <div class="flex items-center gap-2 mb-2">
                                            <label class="text-xs text-gray-600 w-14 shrink-0">Score:</label>
                                            <input type="number" name="practical_score_4" min="0" max="100" placeholder="0-100" required
                                                class="w-full px-2 py-1 text-sm border border-pink-300 rounded focus:ring-1 focus:ring-pink-500"
                                                value="{{ old('practical_score_4') }}" data-hard-skill="hard_skill_4" oninput="updateScoreBadge(this, 'badge-test-4')">
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-600 practical-score-badge" id="badge-test-4">--%</span>
                                        </div>
                                        <textarea name="practical_obs_4" rows="2" required placeholder="List safety protocols checked and any violations observed..."
                                            class="w-full px-2 py-1 text-xs border border-pink-200 rounded focus:ring-1 focus:ring-pink-500">{{ old('practical_obs_4') }}</textarea>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                                        <h6 class="text-xs font-bold text-pink-600 mb-2 flex items-center gap-1"><i class='bx bx-star'></i> Rating</h6>
                                        <div class="mb-2 px-3 py-2 bg-pink-50 rounded flex items-center gap-2" id="ref-hard_skill_4">
                                            <i class='bx bx-bar-chart-alt-2 text-pink-500'></i>
                                            <span class="text-xs text-gray-500">Test Score: </span>
                                            <span class="text-sm font-bold text-gray-800" id="display-score-4">--</span>
                                            <span class="text-xs ml-auto font-medium" id="display-suggest-4">Awaiting score</span>
                                        </div>
                                        <select name="hard_skill_4" id="hard_skill_4" required class="w-full px-3 py-2 border border-pink-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm">
                                            <option value="">Select Rating</option>
                                            <option value="exceptional" {{ old('hard_skill_4') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                            <option value="highly_effective" {{ old('hard_skill_4') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                            <option value="proficient" {{ old('hard_skill_4') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                            <option value="inconsistent" {{ old('hard_skill_4') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                            <option value="unsatisfactory" {{ old('hard_skill_4') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                        </select>
                                        <div class="hidden mt-2 p-2 bg-red-50 border border-red-300 rounded-lg mismatch-warning" id="mismatch-warn-4">
                                            <p class="text-xs text-red-700 font-bold flex items-center gap-1"><i class='bx bx-error-circle'></i> Rating does not match test score!</p>
                                            <p class="text-xs text-red-600 mt-1">You selected a different rating than the test score suggests. Provide a justification (min 50 chars):</p>
                                            <textarea name="override_justification_4" id="override-just-4" rows="2" maxlength="500" placeholder="Explain why you are overriding the suggested rating..."
                                                class="w-full mt-1 px-2 py-1 text-xs border border-red-300 rounded focus:ring-1 focus:ring-red-500">{{ old('override_justification_4') }}</textarea>
                                            <p class="text-xs text-gray-400 mt-1 override-char-count" id="override-chars-4">0 / 50 min characters</p>
                                        </div>
                                        <details class="mt-2">
                                            <summary class="text-xs text-pink-600 cursor-pointer hover:text-pink-800 font-medium"><i class='bx bx-info-circle mr-1'></i>Criteria</summary>
                                            <div class="mt-1 text-xs text-gray-600 space-y-1">
                                                <p><span class="font-bold text-green-600">Exceptional:</span> Proactively identifies hazards; champions safety culture</p>
                                                <p><span class="font-bold text-red-600">Unsatisfactory:</span> Disregards safety protocols; creates risk</p>
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            </div>

                            <!-- Hard Skill 5: Task Efficiency -->
                            <div class="p-4 bg-pink-50 rounded-lg border border-pink-200">
                                <label class="block font-semibold text-gray-800 mb-3">
                                    10. Task Efficiency - Speed and efficiency in completing assigned tasks
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                                        <h6 class="text-xs font-bold text-pink-600 mb-2 flex items-center gap-1"><i class='bx bx-test-tube'></i> Practical Test</h6>
                                        <p class="text-xs text-gray-500 mb-2">Assign a timed task. Record completion time vs. expected benchmark.</p>
                                        <div class="flex items-center gap-2 mb-2">
                                            <label class="text-xs text-gray-600 w-14 shrink-0">Score:</label>
                                            <input type="number" name="practical_score_5" min="0" max="100" placeholder="0-100" required
                                                class="w-full px-2 py-1 text-sm border border-pink-300 rounded focus:ring-1 focus:ring-pink-500"
                                                value="{{ old('practical_score_5') }}" data-hard-skill="hard_skill_5" oninput="updateScoreBadge(this, 'badge-test-5')">
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-600 practical-score-badge" id="badge-test-5">--%</span>
                                        </div>
                                        <textarea name="practical_obs_5" rows="2" required placeholder="Describe the task, time allotted, actual completion time..."
                                            class="w-full px-2 py-1 text-xs border border-pink-200 rounded focus:ring-1 focus:ring-pink-500">{{ old('practical_obs_5') }}</textarea>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                                        <h6 class="text-xs font-bold text-pink-600 mb-2 flex items-center gap-1"><i class='bx bx-star'></i> Rating</h6>
                                        <div class="mb-2 px-3 py-2 bg-pink-50 rounded flex items-center gap-2" id="ref-hard_skill_5">
                                            <i class='bx bx-bar-chart-alt-2 text-pink-500'></i>
                                            <span class="text-xs text-gray-500">Test Score: </span>
                                            <span class="text-sm font-bold text-gray-800" id="display-score-5">--</span>
                                            <span class="text-xs ml-auto font-medium" id="display-suggest-5">Awaiting score</span>
                                        </div>
                                        <select name="hard_skill_5" id="hard_skill_5" required class="w-full px-3 py-2 border border-pink-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm">
                                            <option value="">Select Rating</option>
                                            <option value="exceptional" {{ old('hard_skill_5') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                            <option value="highly_effective" {{ old('hard_skill_5') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                            <option value="proficient" {{ old('hard_skill_5') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                            <option value="inconsistent" {{ old('hard_skill_5') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                            <option value="unsatisfactory" {{ old('hard_skill_5') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                        </select>
                                        <div class="hidden mt-2 p-2 bg-red-50 border border-red-300 rounded-lg mismatch-warning" id="mismatch-warn-5">
                                            <p class="text-xs text-red-700 font-bold flex items-center gap-1"><i class='bx bx-error-circle'></i> Rating does not match test score!</p>
                                            <p class="text-xs text-red-600 mt-1">You selected a different rating than the test score suggests. Provide a justification (min 50 chars):</p>
                                            <textarea name="override_justification_5" id="override-just-5" rows="2" maxlength="500" placeholder="Explain why you are overriding the suggested rating..."
                                                class="w-full mt-1 px-2 py-1 text-xs border border-red-300 rounded focus:ring-1 focus:ring-red-500">{{ old('override_justification_5') }}</textarea>
                                            <p class="text-xs text-gray-400 mt-1 override-char-count" id="override-chars-5">0 / 50 min characters</p>
                                        </div>
                                        <details class="mt-2">
                                            <summary class="text-xs text-pink-600 cursor-pointer hover:text-pink-800 font-medium"><i class='bx bx-info-circle mr-1'></i>Criteria</summary>
                                            <div class="mt-1 text-xs text-gray-600 space-y-1">
                                                <p><span class="font-bold text-green-600">Exceptional:</span> Completes tasks well ahead of deadlines; optimizes processes</p>
                                                <p><span class="font-bold text-red-600">Unsatisfactory:</span> Regularly fails to complete on time; significant delays</p>
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="mt-6 space-y-4">
                            <div>
                                <label class="block font-medium text-gray-800 mb-2">Strengths and Commendations</label>
                                <textarea name="strengths" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Highlight the employee's key strengths...">{{ old('strengths') }}</textarea>
                            </div>
                            <div>
                                <label class="block font-medium text-gray-800 mb-2">Areas for Improvement</label>
                                <textarea name="areas_for_improvement" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Identify areas where the employee can improve...">{{ old('areas_for_improvement') }}</textarea>
                            </div>
                        </div>

                        <!-- Final Decision -->
                        <div class="mt-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                            <h5 class="text-lg font-bold text-gray-800 text-center mb-4">Final Evaluation Decision</h5>
                            <p class="text-center text-gray-600 text-sm mb-6">Based on the assessment scores and hands-on evaluation, make your final decision:</p>
                            
                            <div class="flex justify-center gap-4">
                                @if (auth()->user()->isSupervisor())
                                <button type="submit" name="decision" value="passed" 
                                    class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-lg shadow-lg transition-all duration-200 flex items-center gap-2">
                                    <i class='bx bx-check-circle text-xl'></i> Approve (Pass)
                                </button>
                                
                                <button type="submit" name="decision" value="failed" 
                                    class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-lg shadow-lg transition-all duration-200 flex items-center gap-2">
                                    <i class='bx bx-x-circle text-xl'></i> Reject (Fail)
                                </button>
                                @else
                                <div class="w-full text-center py-4">
                                    <div class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 text-gray-500 rounded-xl font-bold text-lg cursor-not-allowed">
                                        <i class='bx bx-lock-alt text-xl'></i> Only Supervisors Can Submit Evaluations
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>

    <script>
        // ===== RATING INTEGRITY ENFORCEMENT =====
        // Tracks the suggested rating per hard skill (set by test score)
        const suggestedRatings = {};
        const MIN_OBSERVATION_CHARS = 50;
        const MIN_JUSTIFICATION_CHARS = 50;

        // Rating hierarchy for comparison (lower index = higher rating)
        const ratingOrder = ['exceptional', 'highly_effective', 'proficient', 'inconsistent', 'unsatisfactory'];

        // Score-to-Rating mapping
        function scoreToRating(score) {
            if (score >= 90) return { value: 'exceptional', label: 'Exceptional', color: 'text-green-600' };
            if (score >= 75) return { value: 'highly_effective', label: 'Highly Effective', color: 'text-teal-600' };
            if (score >= 60) return { value: 'proficient', label: 'Proficient', color: 'text-blue-600' };
            if (score >= 40) return { value: 'inconsistent', label: 'Inconsistent', color: 'text-yellow-600' };
            return { value: 'unsatisfactory', label: 'Unsatisfactory', color: 'text-red-600' };
        }

        // Update badge and trigger auto-suggest
        function updateScoreBadge(input, badgeId) {
            const badge = document.getElementById(badgeId);
            const score = parseInt(input.value);
            if (isNaN(score) || score < 0 || score > 100) {
                badge.textContent = '--%';
                badge.className = 'text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-600 practical-score-badge';
                return;
            }

            badge.textContent = score + '%';
            let bgColor = 'bg-gray-200', txtColor = 'text-gray-600';
            if (score >= 90) { bgColor = 'bg-green-100'; txtColor = 'text-green-700'; }
            else if (score >= 75) { bgColor = 'bg-teal-100'; txtColor = 'text-teal-700'; }
            else if (score >= 60) { bgColor = 'bg-blue-100'; txtColor = 'text-blue-700'; }
            else if (score >= 40) { bgColor = 'bg-yellow-100'; txtColor = 'text-yellow-700'; }
            else { bgColor = 'bg-red-100'; txtColor = 'text-red-700'; }
            badge.className = `text-xs px-2 py-1 rounded-full ${bgColor} ${txtColor} practical-score-badge`;

            const hardSkillName = input.getAttribute('data-hard-skill');
            const testNum = hardSkillName.replace('hard_skill_', '');
            autoSuggestRating(testNum, score);
        }

        // Auto-suggest AND auto-lock the rating based on test score
        function autoSuggestRating(num, score) {
            const displayScore = document.getElementById('display-score-' + num);
            const displaySuggest = document.getElementById('display-suggest-' + num);
            const select = document.getElementById('hard_skill_' + num);
            if (!displayScore || !displaySuggest || !select) return;

            const rating = scoreToRating(score);
            suggestedRatings[num] = rating.value; // Track what the score suggests

            displayScore.textContent = score + '%';
            displaySuggest.textContent = 'Locked: ' + rating.label;
            displaySuggest.className = 'text-xs ml-auto font-bold ' + rating.color;

            // Always auto-set to match the score
            select.value = rating.value;
            select.setAttribute('data-auto-set', 'true');
            select.setAttribute('data-suggested', rating.value);

            // Clear any existing mismatch warning (score just changed)
            checkMismatch(num);
        }

        // Check if selected rating mismatches the suggested one
        function checkMismatch(num) {
            const select = document.getElementById('hard_skill_' + num);
            const warnDiv = document.getElementById('mismatch-warn-' + num);
            const justTA = document.getElementById('override-just-' + num);
            if (!select || !warnDiv) return;

            const suggested = select.getAttribute('data-suggested');
            const selected = select.value;

            if (suggested && selected && selected !== suggested) {
                // MISMATCH! Show warning + require justification
                warnDiv.classList.remove('hidden');
                select.classList.add('border-red-500', 'ring-2', 'ring-red-300');
                select.classList.remove('border-pink-300');
                if (justTA) justTA.setAttribute('required', 'required');
            } else {
                // Match — hide warning
                warnDiv.classList.add('hidden');
                select.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
                select.classList.add('border-pink-300');
                if (justTA) {
                    justTA.removeAttribute('required');
                    justTA.value = ''; // Clear justification if they go back to match
                }
            }
        }

        // Listen for rating dropdown changes — detect manual overrides
        document.querySelectorAll('select[name^="hard_skill_"]').forEach(function(sel) {
            sel.addEventListener('change', function() {
                const num = this.id.replace('hard_skill_', '');
                this.setAttribute('data-auto-set', 'false');
                checkMismatch(num);
            });
        });

        // Track override justification character count
        document.querySelectorAll('[id^="override-just-"]').forEach(function(ta) {
            ta.addEventListener('input', function() {
                const num = this.id.replace('override-just-', '');
                const counter = document.getElementById('override-chars-' + num);
                const len = this.value.trim().length;
                if (counter) {
                    counter.textContent = len + ' / ' + MIN_JUSTIFICATION_CHARS + ' min characters';
                    counter.className = len >= MIN_JUSTIFICATION_CHARS
                        ? 'text-xs text-green-600 mt-1 override-char-count'
                        : 'text-xs text-red-500 mt-1 override-char-count';
                }
            });
        });

        // Track observation textarea character count (add inline counter)
        document.querySelectorAll('textarea[name^="practical_obs_"]').forEach(function(ta) {
            // Create counter element
            const counter = document.createElement('p');
            counter.className = 'text-xs text-gray-400 mt-1 obs-char-count';
            counter.textContent = '0 / ' + MIN_OBSERVATION_CHARS + ' min characters';
            ta.parentNode.appendChild(counter);

            ta.addEventListener('input', function() {
                const len = this.value.trim().length;
                counter.textContent = len + ' / ' + MIN_OBSERVATION_CHARS + ' min characters';
                counter.className = len >= MIN_OBSERVATION_CHARS
                    ? 'text-xs text-green-600 mt-1 obs-char-count'
                    : 'text-xs text-red-500 mt-1 obs-char-count';
                // Visual border feedback
                if (len >= MIN_OBSERVATION_CHARS) {
                    this.classList.remove('border-red-400');
                    this.classList.add('border-green-400');
                } else {
                    this.classList.remove('border-green-400');
                    this.classList.add('border-red-400');
                }
            });
        });

        // ===== FORM SUBMIT VALIDATION =====
        document.querySelector('form').addEventListener('submit', function(e) {
            let errors = [];

            // 1. Check observation minimum length
            for (let i = 1; i <= 5; i++) {
                const obs = document.querySelector('textarea[name="practical_obs_' + i + '"]');
                if (obs && obs.value.trim().length < MIN_OBSERVATION_CHARS) {
                    errors.push('Hard Skill ' + (i + 5) + ': Observation must be at least ' + MIN_OBSERVATION_CHARS + ' characters (currently ' + obs.value.trim().length + ').');
                    obs.classList.add('border-red-400');
                    obs.focus();
                }
            }

            // 2. Check mismatch overrides have justification
            for (let i = 1; i <= 5; i++) {
                const select = document.getElementById('hard_skill_' + i);
                const suggested = select ? select.getAttribute('data-suggested') : null;
                const selected = select ? select.value : null;

                if (suggested && selected && selected !== suggested) {
                    const justTA = document.getElementById('override-just-' + i);
                    const justLen = justTA ? justTA.value.trim().length : 0;
                    if (justLen < MIN_JUSTIFICATION_CHARS) {
                        errors.push('Hard Skill ' + (i + 5) + ': You overrode the suggested rating ("' + suggested + '" → "' + selected + '"). Justification must be at least ' + MIN_JUSTIFICATION_CHARS + ' characters (currently ' + justLen + ').');
                        if (justTA) {
                            justTA.classList.add('border-red-400');
                            justTA.focus();
                        }
                    }
                }
            }

            // 3. Check that all scores are filled (they're required but double-check)
            for (let i = 1; i <= 5; i++) {
                const scoreInput = document.querySelector('input[name="practical_score_' + i + '"]');
                if (scoreInput && (scoreInput.value === '' || isNaN(parseInt(scoreInput.value)))) {
                    errors.push('Hard Skill ' + (i + 5) + ': Practical test score is required.');
                }
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert('Please fix the following issues before submitting:\n\n' + errors.join('\n'));
                return false;
            }

            // Confirmation
            const decision = e.submitter.value;
            const message = decision === 'passed'
                ? 'Are you sure you want to APPROVE this employee?\n\nThis action is final and will be logged with your evaluator ID, all test scores, ratings, and any override justifications.'
                : 'Are you sure you want to REJECT this employee?\n\nThis action is final and will be logged with your evaluator ID, all test scores, ratings, and any override justifications.';

            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }

            // Disable buttons
            document.querySelectorAll('button[type="submit"]').forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i> Processing...';
            });
        });

        // ===== DISABLE FORM FOR NON-SUPERVISORS =====
        @if (!auth()->user()->isSupervisor())
        (function() {
            // Disable all form inputs, selects, textareas
            const form = document.querySelector('form');
            if (form) {
                form.querySelectorAll('input, select, textarea').forEach(el => {
                    el.disabled = true;
                    el.classList.add('opacity-60', 'cursor-not-allowed', 'bg-gray-100');
                });
            }
        })();
        @endif
    </script>
</x-app-layout>
