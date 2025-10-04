{{-- filepath: resources/views/competency_management/CompetencyCRUD/create.blade.php --}}
<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-6">Create Competency</h2>

                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('competency.competencies.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Competency Name</label>
                        <input type="text" name="competency_name" value="{{ old('competency_name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Framework</label>
                        <select name="framework_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Select Framework</option>
                            @foreach($frameworks as $framework)
                                <option value="{{ $framework->id }}" {{ old('framework_id') == $framework->id ? 'selected' : '' }}>
                                    {{ $framework->framework_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-6 flex flex-wrap -mx-2">
                        <div class="w-full md:w-1/2 px-2">
                            <label class="block text-gray-700 font-medium mb-2">Proficiency Levels</label>
                            <select name="proficiency_levels" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option value="1" {{ old('proficiency_levels') == '1' ? 'selected' : '' }}>1 (Novice)</option>
                                <option value="2" {{ old('proficiency_levels') == '2' ? 'selected' : '' }}>2 (Intermediate)</option>
                                <option value="3" {{ old('proficiency_levels') == '3' ? 'selected' : '' }}>3 (Expert)</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/2 px-2">
                            <label class="block text-gray-700 font-medium mb-2">Status</label>
                            <select name="status" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('competency.frameworks') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancel</a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>