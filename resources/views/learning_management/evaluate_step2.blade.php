<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Hands-on Evaluation</h1>
            <p class="text-gray-600 mt-1">Evaluate employee's practical skills and competencies.</p>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Hands-on Skills Assessment</h3>
                            <div class="card-tools">
                                <a href="{{ route('assessment.results.evaluate', $result->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Review
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Progress Indicator -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="evaluation-progress text-center">
                                        <span class="badge badge-success" style="background-color: #28a745; color: white; padding: 8px 16px; font-size: 14px;">
                                            <i class="fas fa-check"></i> Step 1: Review Completed
                                        </span>
                                        <span class="mx-2">→</span>
                                        <span class="badge badge-primary" style="background-color: #007bff; color: white; padding: 8px 16px; font-size: 14px;">
                                            Step 2 of 2: Hands-on Evaluation
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Employee Summary -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6><strong>Employee:</strong> {{ $assignment->employee_name }}</h6>
                                            <p class="mb-1"><strong>Assessment:</strong> {{ $assignment->quiz_title }}</p>
                                            <p class="mb-0"><strong>Category:</strong> {{ $assignment->category_name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6><strong>Quiz Performance:</strong></h6>
                                            <p class="mb-1">Questions Answered: {{ count($questionsAndAnswers) }}</p>
                                            <p class="mb-0">Attempt: {{ $result->attempt_number }} of {{ $assignment->max_attempts }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                <!-- Hands-on Evaluation Form -->
                            <form method="POST" action="{{ route('assessment.results.submit-evaluation', $result->id) }}">
                                @csrf

                                <!-- Display Validation Errors -->
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <h6><strong>Please correct the following errors:</strong></h6>
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="mb-4">Performance Evaluation</h5>
                                        <p class="text-muted mb-4">Rate each competency area based on the employee's demonstrated performance:</p>
                                    </div>
                                </div>

                                <!-- Performance Rating Legend -->
                                <div class="card mb-4 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><strong>Rating Scale:</strong></h6>
                                        <div class="row text-center">
                                            <div class="col">
                                                <span class="badge" style="background-color: #28a745; color: white; padding: 8px 12px;">Exceptional</span>
                                                <small class="d-block mt-1">Exceeds expectations</small>
                                            </div>
                                            <div class="col">
                                                <span class="badge" style="background-color: #20c997; color: white; padding: 8px 12px;">Highly Effective</span>
                                                <small class="d-block mt-1">Consistently meets high standards</small>
                                            </div>
                                            <div class="col">
                                                <span class="badge" style="background-color: #17a2b8; color: white; padding: 8px 12px;">Proficient</span>
                                                <small class="d-block mt-1">Meets expectations</small>
                                            </div>
                                            <div class="col">
                                                <span class="badge" style="background-color: #ffc107; color: #212529; padding: 8px 12px;">Inconsistent</span>
                                                <small class="d-block mt-1">Sometimes meets expectations</small>
                                            </div>
                                            <div class="col">
                                                <span class="badge" style="background-color: #dc3545; color: white; padding: 8px 12px;">Unsatisfactory</span>
                                                <small class="d-block mt-1">Below expectations</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Performance Competencies -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Performance Competencies Assessment</h6>
                                    </div>
                                    <div class="card-body">
                                        
                                        <!-- Competency 1 -->
                                        <div class="form-group mb-4">
                                            <label class="form-label"><strong>1. Skill and proficiency in carrying out assignment</strong></label>
                                            <select class="form-control" name="competency_1" required>
                                                <option value="">Select Rating</option>
                                                <option value="exceptional" {{ old('competency_1') == 'exceptional' ? 'selected' : '' }}>Exceptional</option>
                                                <option value="highly_effective" {{ old('competency_1') == 'highly_effective' ? 'selected' : '' }}>Highly Effective</option>
                                                <option value="proficient" {{ old('competency_1') == 'proficient' ? 'selected' : '' }}>Proficient</option>
                                                <option value="inconsistent" {{ old('competency_1') == 'inconsistent' ? 'selected' : '' }}>Inconsistent</option>
                                                <option value="unsatisfactory" {{ old('competency_1') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                                            </select>
                                            @error('competency_1')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Competency 2 -->
                                        <div class="form-group mb-4">
                                            <label class="form-label"><strong>2. Possesses skills and knowledge to perform job effectively</strong></label>
                                            <select class="form-control" name="competency_2" required>
                                                <option value="">Select Rating</option>
                                                <option value="exceptional">Exceptional</option>
                                                <option value="highly_effective">Highly Effective</option>
                                                <option value="proficient">Proficient</option>
                                                <option value="inconsistent">Inconsistent</option>
                                                <option value="unsatisfactory">Unsatisfactory</option>
                                            </select>
                                        </div>

                                        <!-- Competency 3 -->
                                        <div class="form-group mb-4">
                                            <label class="form-label"><strong>3. Skill at planning, organizing and prioritizing workload</strong></label>
                                            <select class="form-control" name="competency_3" required>
                                                <option value="">Select Rating</option>
                                                <option value="exceptional">Exceptional</option>
                                                <option value="highly_effective">Highly Effective</option>
                                                <option value="proficient">Proficient</option>
                                                <option value="inconsistent">Inconsistent</option>
                                                <option value="unsatisfactory">Unsatisfactory</option>
                                            </select>
                                        </div>

                                        <!-- Competency 4 -->
                                        <div class="form-group mb-4">
                                            <label class="form-label"><strong>4. Holds self accountable for assigned responsibilities; sees task through to completion, in a timely manner</strong></label>
                                            <select class="form-control" name="competency_4" required>
                                                <option value="">Select Rating</option>
                                                <option value="exceptional">Exceptional</option>
                                                <option value="highly_effective">Highly Effective</option>
                                                <option value="proficient">Proficient</option>
                                                <option value="inconsistent">Inconsistent</option>
                                                <option value="unsatisfactory">Unsatisfactory</option>
                                            </select>
                                        </div>

                                        <!-- Competency 5 -->
                                        <div class="form-group mb-4">
                                            <label class="form-label"><strong>5. Proficiency at improving work methods and procedures as a means toward greater efficiency</strong></label>
                                            <select class="form-control" name="competency_5" required>
                                                <option value="">Select Rating</option>
                                                <option value="exceptional">Exceptional</option>
                                                <option value="highly_effective">Highly Effective</option>
                                                <option value="proficient">Proficient</option>
                                                <option value="inconsistent">Inconsistent</option>
                                                <option value="unsatisfactory">Unsatisfactory</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <!-- Additional Comments -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Evaluation Comments</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="strengths"><strong>Strengths and Commendations</strong></label>
                                            <textarea class="form-control" id="strengths" name="strengths" rows="3" placeholder="Highlight the employee's key strengths and exceptional performance areas..."></textarea>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="areas_for_improvement"><strong>Areas for Improvement</strong></label>
                                            <textarea class="form-control" id="areas_for_improvement" name="areas_for_improvement" rows="3" placeholder="Identify specific areas where the employee can improve..."></textarea>
                                        </div>
                                    </div>
                                </div>                                <!-- Final Decision -->
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="text-center mb-4">Final Evaluation Decision</h5>
                                        <p class="text-center text-muted mb-4">Based on both the quiz answers and hands-on evaluation, make your final decision:</p>
                                        
                                        <div class="text-center">
                                            <button type="submit" name="decision" value="passed" class="btn btn-success btn-lg me-3" onclick="return validateAndConfirm('passed')">
                                                <i class="fas fa-check-circle"></i> Approve (Pass)
                                            </button>
                                            
                                            <button type="submit" name="decision" value="failed" class="btn btn-danger btn-lg" onclick="return validateAndConfirm('failed')">
                                                <i class="fas fa-times-circle"></i> Reject (Fail)
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateAndConfirm(decision) {
            // Check if all competency ratings are selected
            const competencies = ['competency_1', 'competency_2', 'competency_3', 'competency_4', 'competency_5'];
            let allFilled = true;
            let missingFields = [];

            competencies.forEach(function(competency) {
                const select = document.querySelector(`select[name="${competency}"]`);
                if (!select.value) {
                    allFilled = false;
                    missingFields.push(competency.replace('_', ' ').replace('competency', 'Competency'));
                }
            });

            if (!allFilled) {
                alert('Please rate all competency areas before submitting:\n\n' + missingFields.join('\n'));
                return false;
            }

            // Show confirmation based on decision
            const message = decision === 'passed' 
                ? 'Are you sure you want to APPROVE this employee? This decision will be final.'
                : 'Are you sure you want to REJECT this employee? This decision will be final.';
            
            return confirm(message);
        }

        // Add visual feedback for form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitButtons = document.querySelectorAll('button[type="submit"]');
            submitButtons.forEach(function(button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            });
        });
    </script>
</x-app-layout>