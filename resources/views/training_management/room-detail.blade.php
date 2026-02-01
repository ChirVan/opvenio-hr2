<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

<div class="container-fluid px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('training.room.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-emerald-600">
                    <i class='bx bx-tv mr-2'></i>
                    Training Room
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-sm font-medium text-gray-800 md:ml-2">{{ $catalog->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Course Header -->
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-6 md:p-8 mb-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    @if($catalog->framework)
                        <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm text-sm rounded-full">
                            {{ $catalog->framework->framework_name }}
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $catalog->title }}</h1>
                <p class="text-white/80 max-w-2xl">{{ $catalog->description ?? 'No description available' }}</p>
            </div>
            <div class="flex flex-col items-start md:items-end gap-2">
                <div class="flex items-center gap-4 text-sm">
                    <span class="flex items-center gap-1">
                        <i class='bx bx-file'></i>
                        {{ $catalog->materials->count() }} Materials
                    </span>
                    @if($catalog->duration)
                        <span class="flex items-center gap-1">
                            <i class='bx bx-time-five'></i>
                            {{ $catalog->duration }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Materials List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-list-ul text-emerald-600'></i>
                        Course Materials
                    </h2>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($catalog->materials as $index => $material)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-emerald-600 font-semibold">{{ $index + 1 }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-gray-800 mb-1">{{ $material->lesson_title }}</h3>
                                    @if($material->description)
                                        <p class="text-sm text-gray-500 line-clamp-2 mb-2">{{ $material->description }}</p>
                                    @endif
                                    <div class="flex items-center gap-4 text-xs text-gray-400">
                                        @if($material->duration)
                                            <span class="flex items-center gap-1">
                                                <i class='bx bx-time-five'></i>
                                                {{ $material->duration }}
                                            </span>
                                        @endif
                                        @if($material->type)
                                            <span class="flex items-center gap-1">
                                                <i class='bx bx-file'></i>
                                                {{ ucfirst($material->type) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('training.materials.show', [$catalog->id, $material->id]) }}" 
                                   class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors text-sm font-medium flex items-center gap-1">
                                    <i class='bx bx-play-circle'></i>
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class='bx bx-folder-open text-3xl text-gray-400'></i>
                            </div>
                            <h3 class="text-gray-800 font-medium mb-1">No Materials Yet</h3>
                            <p class="text-sm text-gray-500">This course doesn't have any learning materials.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Course Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class='bx bx-info-circle text-emerald-600'></i>
                    Course Information
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Total Materials</span>
                        <span class="font-medium text-gray-800">{{ $catalog->materials->count() }}</span>
                    </div>
                    @if($catalog->duration)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Duration</span>
                            <span class="font-medium text-gray-800">{{ $catalog->duration }}</span>
                        </div>
                    @endif
                    @if($catalog->framework)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Category</span>
                            <span class="font-medium text-gray-800">{{ $catalog->framework->framework_name }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            Active
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class='bx bx-bolt text-emerald-600'></i>
                    Quick Actions
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('training.catalog.show', $catalog->id) }}" 
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors text-gray-600 hover:text-emerald-600">
                        <i class='bx bx-show text-xl'></i>
                        <span class="text-sm font-medium">View Full Details</span>
                    </a>
                    <a href="{{ route('training.catalog.edit', $catalog->id) }}" 
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors text-gray-600 hover:text-emerald-600">
                        <i class='bx bx-edit text-xl'></i>
                        <span class="text-sm font-medium">Edit Course</span>
                    </a>
                    <a href="{{ route('training.assign.create') }}?catalog={{ $catalog->id }}" 
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors text-gray-600 hover:text-emerald-600">
                        <i class='bx bx-user-plus text-xl'></i>
                        <span class="text-sm font-medium">Assign to Employees</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
