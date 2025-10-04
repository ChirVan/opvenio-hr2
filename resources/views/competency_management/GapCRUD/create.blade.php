<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-6">Assign Employee Role Mapping</h2>

                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('competency.gapanalysis.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Employee</label>
                        <select name="employee_id" id="employee_id" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" data-job-role="{{ $employee->job_role }}">
                                    {{ $employee->lastname }}, {{ $employee->firstname }}
                                </option>
                            @endforeach
                        </select>
                        <div id="jobRoleDisplay" class="mt-2 text-sm text-gray-600"></div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Competency</label>
                        <select name="competency_id" id="competency_id" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Select Competency</option>
                            @foreach($roleMappings as $mapping)
                                <option value="{{ $mapping->competency->id ?? '' }}"
                                    data-framework="{{ $mapping->framework->framework_name ?? '' }}"
                                    data-level="{{ $mapping->proficiency_level ?? '' }}"
                                    data-role-name="{{ $mapping->role_name }}">
                                    {{ $mapping->competency->competency_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Framework</label>
                        <input type="text" name="framework" id="framework" class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Level</label>
                        <input type="text" name="proficiency_level" id="proficiency_level" class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Notes</label>
                        <textarea name="notes" class="w-full border border-gray-300 rounded-md px-3 py-2" rows="3">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const employeeSelect = document.getElementById('employee_id');
            const jobRoleDisplay = document.getElementById('jobRoleDisplay');
            const competencySelect = document.getElementById('competency_id');
            const frameworkInput = document.getElementById('framework');
            const levelInput = document.getElementById('proficiency_level');

            function updateJobRoleDisplay() {
                const selectedEmployee = employeeSelect.options[employeeSelect.selectedIndex];
                const jobRole = selectedEmployee.getAttribute('data-job-role');
                jobRoleDisplay.textContent = jobRole ? 'Job Role: ' + jobRole : '';

                // Filter competencies by job role
                Array.from(competencySelect.options).forEach(option => {
                    if (!option.value) return; // Skip placeholder
                    option.style.display = (option.getAttribute('data-role-name') === jobRole) ? '' : 'none';
                });
                competencySelect.value = '';
                frameworkInput.value = '';
                levelInput.value = '';
            }

            function updateFrameworkAndLevel() {
                const selected = competencySelect.options[competencySelect.selectedIndex];
                frameworkInput.value = selected.getAttribute('data-framework') || '';
                levelInput.value = selected.getAttribute('data-level') || '';
            }

            employeeSelect.addEventListener('change', updateJobRoleDisplay);
            competencySelect.addEventListener('change', updateFrameworkAndLevel);

            // On page load, if old input exists, trigger filtering
            if (employeeSelect.value) updateJobRoleDisplay();
            if (competencySelect.value) updateFrameworkAndLevel();
        });
    </script>
</x-app-layout>