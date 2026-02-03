{{-- resources/views/layouts/sidebar.blade.php --}}
@php
    // Server-side active detection for competency submenu
    $isCompetencyOpen = request()->routeIs('competency.*');
    // Server-side active detection for training submenu
    $isTrainingOpen = request()->routeIs('training.*');
    // Server-side active detection for learning submenu
    $isLearningOpen = request()->routeIs('learning.*') || request()->routeIs('assessment.results*');
    // Server-side active detection for succession planning submenu
    $isSuccessionOpen = request()->routeIs('succession.*');
@endphp
<aside id="sidebar" class="fixed left-0 shadow-card z-40 overflow-y-auto active" 
       style="background: var(--color-primary); 
              border-color: var(--color-primary); 
              top: var(--navbar-height); 
              height: calc(100vh - var(--navbar-height)); 
              width: 250px;
              border-width: 1.5px;">
    
    <!-- Dashboard -->
    <div class="px-2">
        <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-2 hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class='bx bx-home text-lg text-white flex-shrink-0'></i>
            <span class="sidebar-text text-white text-sm">Dashboard</span>
        </a>
    </div>
    
    <hr class="my-3 border-t border-gray-200 border-opacity-30">
    
    <div class="text-white text-opacity-70 text-xs font-bold uppercase mb-2 ml-5 sidebar-text">Main Content</div>
    
    <nav class="flex flex-col gap-2 px-2">
       
        <div class="relative">
            <button id="competencyToggle" class="sidebar-dropdown-btn w-full flex items-center justify-between hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg px-3 py-2.5 {{ $isCompetencyOpen ? 'open active' : '' }}">
                <span class="flex items-center gap-2 min-w-0">
                    <i class='bx bx-target-lock text-lg text-white flex-shrink-0'></i>
                    <span class="sidebar-text text-white text-sm leading-tight">Competency<br>Management</span>
                </span>
                <i id="competencyChevron" class='bx bx-chevron-down text-white text-base flex-shrink-0 transition-transform duration-200 {{ $isCompetencyOpen ? 'rotate-180' : '' }}'></i>
            </button>

            <div id="competencyMenu" class="mt-2 ml-2 {{ $isCompetencyOpen ? '' : 'hidden' }}">
                <div class="bg-white bg-opacity-10 rounded-lg p-1">
                    <a href="{{ route('competency.frameworks') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('competency.frameworks') ? 'active' : '' }}">
                        <i class='bx bx-list-ul text-base'></i>
                        <span>Competencies</span>
                    </a>
                    <a href="{{ route('competency.rolemapping') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('competency.rolemapping*') ? 'active' : '' }}">
                        <i class='bx bx-map text-base'></i>
                        <span>Role Mapping</span>
                    </a>
                    <a href="{{ route('competency.gap-analysis') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('competency.gap-analysis*') ? 'active' : '' }}">
                        <i class='bx bx-bar-chart-alt-2 text-base'></i>
                        <span>Gap Analysis</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="relative">
            <button id="trainingToggle" class="sidebar-dropdown-btn w-full flex items-center justify-between hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg px-3 py-2.5 {{ $isTrainingOpen ? 'open active' : '' }}">
                <span class="flex items-center gap-2 min-w-0">
                    <i class='bx bx-dumbbell text-lg text-white flex-shrink-0'></i>
                    <span class="sidebar-text text-white text-sm">Training Management</span>
                </span>
                <i id="trainingChevron" class='bx bx-chevron-down text-white text-base flex-shrink-0 transition-transform duration-200 {{ $isTrainingOpen ? 'rotate-180' : '' }}'></i>
            </button>

            <div id="trainingMenu" class="mt-2 ml-2 {{ $isTrainingOpen ? '' : 'hidden' }}">
                <div class="bg-white bg-opacity-10 rounded-lg p-1">
                    <a href="{{ route('training.catalog.index') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('training.catalog.*') ? 'active' : '' }}">
                        <i class='bx bx-book-content text-base'></i>
                        <span>Training Catalog</span>
                    </a>
                    <a href="{{ route('training.assign.index') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('training.assign.*') ? 'active' : '' }}">
                        <i class='bx bx-user-plus text-base'></i>
                        <span>Assign Training</span>
                    </a>
                    <a href="{{ route('training.grant-request.index') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('training.grant-request.*') ? 'active' : '' }}">
                        <i class='bx bx-check-circle text-base'></i>
                        <span>Grant Request</span>
                    </a>
                    <a href="{{ route('training.room.index') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('training.room.*') ? 'active' : '' }}">
                        <i class='bx bx-door-open text-base'></i>
                        <span>Training Room</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="relative">
            <button id="learningToggle" class="sidebar-dropdown-btn w-full flex items-center justify-between hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg px-3 py-2.5 {{ $isLearningOpen ? 'open active' : '' }}">
                <span class="flex items-center gap-2 min-w-0">
                    <i class='bx bx-book-open text-lg text-white flex-shrink-0'></i>
                    <span class="sidebar-text text-white text-sm">Learning Management</span>
                </span>
                <i id="learningChevron" class='bx bx-chevron-down text-white text-base flex-shrink-0 transition-transform duration-200 {{ $isLearningOpen ? 'rotate-180' : '' }}'></i>
            </button>

            <div id="learningMenu" class="mt-2 ml-2 {{ request()->routeIs('learning.*') || request()->routeIs('assessment.results*') ? '' : 'hidden' }}">
                <div class="bg-white bg-opacity-10 rounded-lg p-1">
                    <a href="{{ route('learning.assessment') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('learning.assessment') ? 'active' : '' }}">
                        <i class='bx bx-building-house text-base'></i>
                        <span>Assessment Center</span>
                    </a>
                    <a href="{{ route('learning.hub') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('learning.hub') ? 'active' : '' }}">
                        <i class='bx bx-grid-alt text-base'></i>
                        <span>Assessment Hub</span>
                    </a>
                    <a href="{{ route('learning.self-assess') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('learning.self-assess') ? 'active' : '' }}">
                        <i class='bx bx-user-check text-base'></i>
                        <span>Self Assessment</span>
                    </a>
                    <a href="{{ route('assessment.results') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('assessment.results*') ? 'active' : '' }}">
                        <i class='bx bx-trophy text-base'></i>
                        <span>Assessment Results</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="relative">
            <button id="successionToggle" class="sidebar-dropdown-btn w-full flex items-center justify-between hover:bg-white hover:bg-opacity-10 transition-colors duration-200 rounded-lg px-3 py-2.5 {{ $isSuccessionOpen ? 'open active' : '' }}">
                <span class="flex items-center gap-2 min-w-0">
                    <i class='bx bx-git-branch text-lg text-white flex-shrink-0'></i>
                    <span class="sidebar-text text-white text-sm">Succession Planning</span>
                </span>
                <i id="successionChevron" class='bx bx-chevron-down text-white text-base flex-shrink-0 transition-transform duration-200 {{ $isSuccessionOpen ? 'rotate-180' : '' }}'></i>
            </button>

            <div id="successionMenu" class="mt-2 ml-2 {{ $isSuccessionOpen ? '' : 'hidden' }}">
                <div class="bg-white bg-opacity-10 rounded-lg p-1">
                    <a href="{{ route('succession.successors') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('succession.successors') ? 'active' : '' }}">
                        <i class='bx bx-user-voice text-base'></i>
                        <span>Successors</span>
                    </a>
                    <a href="{{ route('succession.potential-successors') }}" class="flex items-center gap-2 text-white hover:bg-white hover:bg-opacity-20 rounded-md px-3 py-2 text-sm {{ request()->routeIs('succession.potential-successors') ? 'active' : '' }}">
                        <i class='bx bx-group text-base'></i>
                        <span>Talent Pool</span>
                    </a>
                </div>
            </div>
        </div>
        
    </nav>
    
    
</aside>

<style>
    /* Enhanced sidebar styling */
    #sidebar {
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        min-width: 250px;
        padding: 10px 6px;
    }
    
    #sidebar.collapsed {
        width: 60px !important;
        min-width: 60px;
    }
    
    #sidebar.collapsed .sidebar-text {
        display: none;
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
    
    /* Menu button text styling */
    #sidebar .sidebar-text {
        white-space: nowrap;
    }
    
    /* Dropdown button styling */
    .sidebar-dropdown-btn {
        text-align: left;
        background: transparent;
        border: none;
        cursor: pointer;
    }
    
    .sidebar-dropdown-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-dropdown-btn.active {
        background: rgba(255, 255, 255, 0.08);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        #sidebar {
            width: 250px;
            min-width: 250px;
        }
    }

    /* Submenu styling */
    #competencyMenu a,
    #trainingMenu a,
    #learningMenu a,
    #successionMenu a { 
        display: block;
        white-space: nowrap;
    }
    
    #competencyMenu .rounded-md,
    #trainingMenu .rounded-md,
    #learningMenu .rounded-md,
    #successionMenu .rounded-md { 
        color: #ffffff; 
    }

    /* Active submenu link style */
    #competencyMenu a.active,
    #trainingMenu a.active,
    #learningMenu a.active,
    #successionMenu a.active {
        background: rgba(255,255,255,0.18);
        color: #ffffff;
        font-weight: 600;
    }

    /* Parent dropdown active state */
    #competencyToggle.active,
    #trainingToggle.active,
    #learningToggle.active,
    #successionToggle.active {
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