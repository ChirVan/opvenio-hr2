<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">
            <h2 class="text-2xl font-semibold mb-6">Create Role Mapping</h2>

            @if ($errors->any())
                <div class="mb-4">
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('competency.rolemapping.store') }}" method="POST" id="role-mapping-form">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Role</label>
                    <select name="role_name" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="General Manager" {{ old('role_name') == 'general_manager' ? 'selected' : '' }}>General Manager</option>
                        <option value="Accounting Clerk" {{ old('role_name') == 'accounting_clerk' ? 'selected' : '' }}>Accounting Clerk</option>
                        <option value="Loan Officer" {{ old('role_name') == 'loan-officer' ? 'selected' : '' }}>Loan Officer</option>
                        <option value="Cashier" {{ old('role_name') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                        <option value="Book keeper" {{ old('role_name') == 'bookkeeper' ? 'selected' : '' }}>Book Keeper</option>
                        <option value="Treasurer" {{ old('role_name') == 'treasurer' ? 'selected' : '' }}>Treasurer</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Competency</label>
                    <select name="competency_id" id="competency_id" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="">Select Competency</option>
                        @foreach($competencies as $competency)
                            <option 
                                value="{{ $competency->id }}"
                                data-framework-id="{{ $competency->framework_id }}"
                                data-framework-name="{{ $competency->framework->framework_name ?? '' }}"
                                data-level="{{ $competency->proficiency_levels ?? '' }}"
                                {{ old('competency_id') == $competency->id ? 'selected' : '' }}
                            >
                                {{ $competency->competency_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Framework</label>
                    <input type="text" id="framework_name" class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    <input type="hidden" name="framework_id" id="framework_id">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Proficiency Level</label>
                    <input type="text" name="proficiency_level" id="level" value="{{ old('proficiency_level') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Notes</label>
                    <textarea name="notes" class="w-full border border-gray-300 rounded-md px-3 py-2">{{ old('notes') }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Status</label>
                    <select name="status" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('competency.rolemapping') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancel</a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Create</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const competencySelect = document.getElementById('competency_id');
            const frameworkNameInput = document.getElementById('framework_name');
            const frameworkIdInput = document.getElementById('framework_id');
            const levelInput = document.getElementById('level');

            function updateFrameworkAndLevel() {
                const selected = competencySelect.options[competencySelect.selectedIndex];
                frameworkNameInput.value = selected.getAttribute('data-framework-name') || '';
                frameworkIdInput.value = selected.getAttribute('data-framework-id') || '';
                levelInput.value = selected.getAttribute('data-level') || '';
            }

            competencySelect.addEventListener('change', updateFrameworkAndLevel);

            // On page load, if a competency is already selected (old input), update fields
            updateFrameworkAndLevel();
        });
    </script>
</x-app-layout>