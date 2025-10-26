{{-- resources/views/layouts/sidebar.blade.php --}}
@php
    // Server-side active detection for competency submenu
    $isCompetencyOpen = request()->routeIs('competency.*');
    // Server-side active detection for training submenu
    $isTrainingOpen = request()->routeIs('training.*');
    // Server-side active detection for learning submenu
    $isLearningOpen = request()->routeIs('learning.*');
    // Server-side active detection for succession planning submenu
    $isSuccessionOpen = request()->routeIs('succession.*');
@endphp
<aside id="sidebar" class="fixed left-0 shadow-card p-4 z-40 overflow-y-auto active" 
       style="background: var(--color-primary); 
              border-color: var(--color-primary); 
              top: var(--navbar-height); 
              height: calc(100vh - var(--navbar-height)); 
              border-width: 1.5px;">
    
    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}" class="btn btn-primary w-full  gap-3 hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg p-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class='bx bx-home text-xl'></i>
        <span class="sidebar-text">Dashboard</span>
    </a>
    
    <hr class="my-3 border-t border-gray-200 border-opacity-30">
    
    <div class="text-white text-opacity-70 text-xs font-bold uppercase mb-2 sidebar-text">Main Content</div>
    
    <nav class="flex flex-col gap-2">
       
        <div class="relative">
            <button id="competencyToggle" class="btn btn-primary w-full flex items-center justify-between gap-3 hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg p-3 {{ $isCompetencyOpen ? 'open active' : '' }}">
                <span class="flex items-center gap-2">
                    <i class='bx bx-target-lock text-lg text-white'></i>
                    <span class="sidebar-text text-white text-base">Competency Management</span>
                    <i id="competencyChevron" class='bx bx-chevron-down text-white text-base ml-2 {{ $isCompetencyOpen ? 'rotate-180' : '' }}'></i>
                </span>
            </button>

            <div id="competencyMenu" class="mt-2 ml-2 {{ $isCompetencyOpen ? '' : 'hidden' }}">
                <div class="bg-white bg-opacity-10 rounded-lg p-1">
                    <a href="{{ route('competency.frameworks') }}" class="block text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 {{ request()->routeIs('competency.frameworks') ? 'active' : '' }}">Competencies</a>
                    <a href="{{ route('competency.gapanalysis') }}" class="block text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 {{ request()->routeIs('competency.gapanalysis') ? 'active' : '' }}">Gap Analysis</a>
                </div>
            </div>
        </div>
        
        <div class="relative">
            <button id="trainingToggle" class="btn btn-primary w-full flex items-center justify-between gap-3 hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg p-3 {{ $isTrainingOpen ? 'open active' : '' }}">
                <span class="flex items-center gap-2">
                    <i class='bx bx-dumbbell text-lg text-white'></i>
                    <span class="sidebar-text text-white text-base">Training Management</span>
                    <i id="trainingChevron" class='bx bx-chevron-down text-white text-base ml-2 {{ $isTrainingOpen ? 'rotate-180' : '' }}'></i>
                </span>
            </button>

            <div id="trainingMenu" class="mt-2 ml-2 {{ $isTrainingOpen ? '' : 'hidden' }}">
                <div class="bg-white bg-opacity-10 rounded-lg p-1">
                    <a href="{{ route('training.catalog.index') }}" class="block text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 {{ request()->routeIs('training.catalog.*') ? 'active' : '' }}">Training Catalog</a>
                    <a href="{{ route('training.assign.index') }}" class="block text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 {{ request()->routeIs('training.assign.*') ? 'active' : '' }}">Assign Training</a>
                </div>
            </div>
        </div>
        
        <div class="relative">
            <button id="learningToggle" class="btn btn-primary w-full flex items-center justify-between gap-3 hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg p-3 {{ request()->routeIs('learning.*') ? 'open active' : '' }}">
                <span class="flex items-center gap-2">
                    <i class='bx bx-book-open text-lg text-white'></i>
                    <span class="sidebar-text text-white text-base">Learning Management</span>
                    <i id="learningChevron" class='bx bx-chevron-down text-white text-base ml-2 {{ request()->routeIs('learning.*') ? 'rotate-180' : '' }}'></i>
                </span>
            </button>

            <div id="learningMenu" class="mt-2 ml-2 {{ request()->routeIs('learning.*') ? '' : 'hidden' }}">
                <div class="bg-white bg-opacity-10 rounded-lg p-1">
                    <a href="{{ route('learning.assessment') }}" class="block text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 {{ request()->routeIs('learning.assessment') ? 'active' : '' }}">Assessment Center</a>
                    <a href="{{ route('learning.hub') }}" class="block text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 {{ request()->routeIs('learning.hub') ? 'active' : '' }}">Assessment Hub</a>
                </div>
            </div>
        </div>
        
        <div class="relative">
            <button id="successionToggle" class="btn btn-primary w-full flex items-center justify-between gap-3 hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg p-3 {{ $isSuccessionOpen ? 'open active' : '' }}">
                <span class="flex items-center gap-2">
                    <i class='bx bx-git-branch text-lg text-white'></i>
                    <span class="sidebar-text text-white text-base">Succession Planning</span>
                    <i id="successionChevron" class='bx bx-chevron-down text-white text-base ml-2 {{ $isSuccessionOpen ? 'rotate-180' : '' }}'></i>
                </span>
            </button>

            <div id="successionMenu" class="mt-2 ml-2 {{ $isSuccessionOpen ? '' : 'hidden' }}">
                <div class="bg-white bg-opacity-10 rounded-lg p-1">
                    <a href="{{ route('succession.successors') }}" class="block text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 {{ request()->routeIs('succession.successors') ? 'active' : '' }}">Successors</a>
                    <a href="{{ route('succession.potential-successors') }}" class="block text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 {{ request()->routeIs('succession.potential-successors') ? 'active' : '' }}">Talent Pool</a>
                </div>
            </div>
        </div>
        
    </nav>
    
    <!-- Optional: Add collapse toggle inside sidebar -->
    <div class="mt-6 pt-4 border-t border-gray-200 border-opacity-30">
        <button id="sidebarCollapseInner" class="btn btn-primary flex items-center gap-3 w-full hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg p-3">
            <i class='bx bx-chevrons-left text-xl text-white' id="collapseIcon"></i>
            <span class="sidebar-text text-white">Collapse Menu</span>
        </button>
    </div>
</aside>

<style>
    /* Enhanced sidebar styling */
    #sidebar {
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
    }
    
    /* Smooth hover effects for sidebar items */
    #sidebar a:hover {
        transform: translateX(4px);
        transition: all 0.2s ease;
    }
    
    /* Custom scrollbar for sidebar */
    #sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    #sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }
    
    #sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }
    
    #sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
    
    /* Active/current page styling */
    #sidebar a.active {
        background: rgba(255, 255, 255, 0.15);
        border-left: 4px solid #ffffff;
        padding-left: calc(0.75rem - 4px);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        #sidebar {
            width: 280px; /* Slightly wider on mobile */
        }
    }

    /* Submenu styling */
    #competencyMenu a { display: block; }
    #competencyMenu .rounded-md { color: #ffffff; }

    /* Active submenu link style */
    #competencyMenu a.active {
        background: rgba(255,255,255,0.18);
        color: #ffffff;
        font-weight: 600;
    }

    /* Parent dropdown active state */
    #competencyToggle.active {
        background: rgba(255,255,255,0.06);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.03);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inner collapse button functionality
        const innerCollapseBtn = document.getElementById('sidebarCollapseInner');
        const collapseIcon = document.getElementById('collapseIcon');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        if (innerCollapseBtn && toggleBtn) {
            innerCollapseBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // Trigger the main toggle function
                toggleBtn.click();
            });
        }
        
        // Competency dropdown elements
    const competencyToggle = document.getElementById('competencyToggle');
    const competencyMenu = document.getElementById('competencyMenu');
    const competencyChevron = document.getElementById('competencyChevron');
        
        // Training dropdown elements
    const trainingToggle = document.getElementById('trainingToggle');
    const trainingMenu = document.getElementById('trainingMenu');
    const trainingChevron = document.getElementById('trainingChevron');
        
        // Learning dropdown elements
    const learningToggle = document.getElementById('learningToggle');
    const learningMenu = document.getElementById('learningMenu');
    const learningChevron = document.getElementById('learningChevron');
        
        // Succession Planning dropdown elements
    const successionToggle = document.getElementById('successionToggle');
    const successionMenu = document.getElementById('successionMenu');
    const successionChevron = document.getElementById('successionChevron');
        
        // Optional: Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            // Alt + S to toggle sidebar
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                if (toggleBtn) toggleBtn.click();
            }
        });

        if (competencyToggle) {
            competencyToggle.addEventListener('click', function(e) {
                e.preventDefault();
                const isOpen = !competencyMenu.classList.contains('hidden');
                competencyMenu.classList.toggle('hidden');
                competencyToggle.classList.toggle('open', !isOpen);
                competencyChevron.classList.toggle('rotate-180', !isOpen);
                competencyToggle.classList.toggle('active', !isOpen);
            });
        }

        if (trainingToggle) {
            trainingToggle.addEventListener('click', function(e) {
                e.preventDefault();
                const isOpen = !trainingMenu.classList.contains('hidden');
                trainingMenu.classList.toggle('hidden');
                trainingToggle.classList.toggle('open', !isOpen);
                trainingChevron.classList.toggle('rotate-180', !isOpen);
                trainingToggle.classList.toggle('active', !isOpen);
            });
        }

        if (learningToggle) {
            learningToggle.addEventListener('click', function(e) {
                e.preventDefault();
                const isOpen = !learningMenu.classList.contains('hidden');
                learningMenu.classList.toggle('hidden');
                learningToggle.classList.toggle('open', !isOpen);
                learningChevron.classList.toggle('rotate-180', !isOpen);
                learningToggle.classList.toggle('active', !isOpen);
            });
        }

        if (successionToggle) {
            successionToggle.addEventListener('click', function(e) {
                e.preventDefault();
                const isOpen = !successionMenu.classList.contains('hidden');
                successionMenu.classList.toggle('hidden');
                successionToggle.classList.toggle('open', !isOpen);
                successionChevron.classList.toggle('rotate-180', !isOpen);
                successionToggle.classList.toggle('active', !isOpen);
            });
        }
    });
</script>