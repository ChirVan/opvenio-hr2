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
            <h1 class="text-3xl font-bold text-gray-900">Evaluate Assessment</h1>
            <p class="text-gray-600 mt-1">Review employee answers and make evaluation decision.</p>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Evaluate Assessment</h3>
                            <div class="card-tools">
                                <a href="{{ route('assessment.results') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Results
                                </a>
                            </div>
                        </div>
                <div class="card-body">
                    <!-- Employee Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Employee Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $assignment->employee_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $assignment->employee_email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date Taken:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($result->completed_at)->format('M d, Y H:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Assessment Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Quiz:</strong></td>
                                    <td>{{ $assignment->quiz_title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>{{ $assignment->category_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Attempt:</strong></td>
                                    <td>{{ $result->attempt_number }} of {{ $assignment->max_attempts }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Questions:</strong></td>
                                    <td>{{ $result->total_questions }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Questions and Answers -->
                    <div class="row">
                        <div class="col-12">
                            <h5>Questions and Answers</h5>
                            @foreach($questionsAndAnswers as $index => $qa)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Question {{ $index + 1 }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <strong>Question:</strong>
                                            <p class="mt-2">{{ $qa->question_text }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Employee's Answer:</strong>
                                            <div class="p-2 bg-light border rounded mt-2">
                                                {{ $qa->user_answer ?: 'No answer provided' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Correct Answer:</strong>
                                            <div class="p-2 bg-success text-white border rounded mt-2">
                                                {{ $qa->correct_answer }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="evaluation-progress">
                                    <span class="badge badge-primary" style="background-color: #007bff; color: white; padding: 8px 16px; font-size: 14px;">Step 1 of 2: Review Answers</span>
                                </div>
                                
                                @if($result->status == 'completed')
                                <div>
                                    <a href="{{ route('assessment.results.evaluate.step2', $result->id) }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-arrow-right"></i> Next: Hands-on Evaluation
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($result->status != 'completed')
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <h5>
                                    @if($result->status == 'passed')
                                        <i class="fas fa-check-circle text-success"></i> This assessment has been approved
                                    @elseif($result->status == 'failed')
                                        <i class="fas fa-times-circle text-danger"></i> This assessment has been rejected
                                    @endif
                                </h5>
                                <p class="mb-0">
                                    Evaluated on: {{ $result->evaluated_at ? \Carbon\Carbon::parse($result->evaluated_at)->format('M d, Y H:i A') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>