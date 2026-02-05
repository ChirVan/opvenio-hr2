<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6 px-4">
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
                    <form method="POST" action="{{ route('training.evaluation.submit', $employee->employee_id) }}" class="p-6">
                        @csrf
                        
                        <input type="hidden" name="result_ids" value="{{ $results->pluck('result_id')->implode(',') }}">

                        @if($errors->any())
                            <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Competencies -->
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
                                <button type="submit" name="decision" value="passed" 
                                    class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-lg shadow-lg transition-all duration-200 flex items-center gap-2">
                                    <i class='bx bx-check-circle text-xl'></i> Approve (Pass)
                                </button>
                                
                                <button type="submit" name="decision" value="failed" 
                                    class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-lg shadow-lg transition-all duration-200 flex items-center gap-2">
                                    <i class='bx bx-x-circle text-xl'></i> Reject (Fail)
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add confirmation before submitting
        document.querySelector('form').addEventListener('submit', function(e) {
            const decision = e.submitter.value;
            const message = decision === 'passed' 
                ? 'Are you sure you want to APPROVE this employee? This action is final.'
                : 'Are you sure you want to REJECT this employee? This action is final.';
            
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }

            // Disable buttons to prevent double submit
            const buttons = document.querySelectorAll('button[type="submit"]');
            buttons.forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i> Processing...';
            });
        });
    </script>
</x-app-layout>
