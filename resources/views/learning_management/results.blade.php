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
            <h1 class="text-3xl font-bold text-gray-900">Assessment Results</h1>
            <p class="text-gray-600 mt-1">Review and evaluate employee assessment submissions.</p>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Assessment Results</h3>
                        </div>
                        <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="background-color: #343a40; color: white;">Employee Name</th>
                                    <th style="background-color: #343a40; color: white;">Employee Email</th>
                                    <th style="background-color: #343a40; color: white;">Quiz Title</th>
                                    <th style="background-color: #343a40; color: white;">Category</th>
                                    <th style="background-color: #343a40; color: white;">Date Taken</th>
                                    <th style="background-color: #343a40; color: white;">Attempts Used</th>
                                    <th style="background-color: #343a40; color: white;">Max Attempts</th>
                                    <th style="background-color: #343a40; color: white;">Status</th>
                                    <th style="background-color: #343a40; color: white;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $result)
                                <tr style="background-color: white;">
                                    <td style="font-weight: 600; color: #495057;">{{ $result->employee_name }}</td>
                                    <td style="color: #6c757d;">{{ $result->employee_email }}</td>
                                    <td style="color: #495057;">{{ $result->quiz_title }}</td>
                                    <td style="color: #495057;">{{ $result->category_name }}</td>
                                    <td style="color: #495057;">{{ $result->completed_at ? \Carbon\Carbon::parse($result->completed_at)->format('M d, Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge" style="background-color: #17a2b8; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">{{ $result->attempt_number }}</span>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: #6c757d; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">{{ $result->max_attempts }}</span>
                                    </td>
                                    <td>
                                        @if($result->status == 'completed')
                                            <span class="badge" style="background-color: #ffc107; color: #212529; padding: 6px 12px; font-size: 12px; font-weight: bold;">Pending Review</span>
                                        @elseif($result->status == 'passed')
                                            <span class="badge" style="background-color: #28a745; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">Approved</span>
                                        @elseif($result->status == 'failed')
                                            <span class="badge" style="background-color: #dc3545; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">Rejected</span>
                                        @else
                                            <span class="badge" style="background-color: #6c757d; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">{{ ucfirst($result->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($result->status == 'completed')
                                            <a href="{{ route('assessment.results.evaluate', $result->id) }}" 
                                               class="btn btn-sm" style="background-color: #007bff; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none;">
                                                <i class="fas fa-eye"></i> Evaluate
                                            </a>
                                        @else
                                            <a href="{{ route('assessment.results.evaluate', $result->id) }}" 
                                               class="btn btn-sm" style="background-color: #17a2b8; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none;">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No assessment results found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>