{{-- resources/views/layouts/sidebar.blade.php - Matching reference design exactly --}}
@php
    $isCompetencyOpen = request()->routeIs('competency.*');
    $isTrainingOpen = request()->routeIs('training.*');
    $isLearningOpen = request()->routeIs('learning.*') || request()->routeIs('assessment.results*');
    $isSuccessionOpen = request()->routeIs('succession.*');
@endphp

<!-- Overlay (mobile) -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/30 hidden opacity-0 transition-opacity duration-300 z-[35] md:hidden"></div>

<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed top-0 left-0 h-full bg-white border-r border-gray-100 shadow-sm z-40">

    <!-- Logo Header -->
    <div class="h-16 flex items-center px-4 border-b border-gray-100">
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 w-full rounded-xl px-2 py-2
                   hover:bg-gray-100 active:bg-gray-200 transition group">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10">
            <div class="leading-tight">
                <div class="font-bold text-gray-800 group-hover:text-emerald-600 transition-colors">
                    Microfinance HR
                </div>
                <div class="text-[11px] text-gray-500 font-semibold uppercase group-hover:text-emerald-600 transition-colors">
                    HUMAN RESOURCE II
                </div>
            </div>
        </a>
    </div>

    <!-- Sidebar Content -->
    <div class="px-4 py-4 overflow-y-auto h-[calc(100%-4rem)] custom-scrollbar">
        
        <!-- MAIN MENU Section -->
        <div class="text-xs font-bold text-gray-400 tracking-wider px-2">MAIN MENU</div>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="mt-3 flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.99]
                   {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-emerald-600 hover:translate-x-1' }}">
            <span class="flex items-center gap-3 font-semibold">
                <span class="inline-flex w-9 h-9 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white/15' : 'bg-emerald-50' }} items-center justify-center">
                    <i class='bx bx-home text-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'text-emerald-600' }}'></i>
                </span>
                <span class="sidebar-text">Dashboard</span>
            </span>
        </a>

        <!-- TEAM MANAGEMENT Section -->
        <div class="text-xs font-bold text-gray-400 tracking-wider px-2 mt-6">COMPETENCY & TRAINING</div>

        <!-- Competency Management Dropdown -->
        <button id="competency-menu-btn"
            class="mt-3 w-full flex items-center justify-between px-4 py-3 rounded-xl
                   {{ $isCompetencyOpen ? 'bg-emerald-600 text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-emerald-600' }}
                   transition-all duration-200 hover:translate-x-1 active:translate-x-0 active:scale-[0.99] font-semibold">
            <span class="flex items-center gap-3">
                <span class="inline-flex w-9 h-9 rounded-lg {{ $isCompetencyOpen ? 'bg-white/15' : 'bg-emerald-50' }} items-center justify-center">
                    <i class='bx bx-target-lock text-lg {{ $isCompetencyOpen ? 'text-white' : 'text-emerald-600' }}'></i>
                </span>
                <span class="sidebar-text">Competency Mgmt</span>
            </span>
            <svg id="competency-arrow" class="w-4 h-4 {{ $isCompetencyOpen ? 'text-white rotate-180' : 'text-emerald-400' }} transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div id="competency-submenu" class="submenu {{ $isCompetencyOpen ? 'is-open' : '' }} mt-1">
            <div class="pl-4 pr-2 py-2 space-y-1 border-l-2 border-gray-100 ml-6">
                <a href="{{ route('competency.frameworks') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('competency.frameworks') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Competencies
                </a>
                <a href="{{ route('competency.rolemapping') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('competency.rolemapping*') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Role Mapping
                </a>
                <a href="{{ route('competency.gap-analysis') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('competency.gap-analysis*') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Gap Analysis
                </a>
            </div>
        </div>

        <!-- Training Management Dropdown -->
        <button id="training-menu-btn"
            class="mt-3 w-full flex items-center justify-between px-4 py-3 rounded-xl
                   {{ $isTrainingOpen ? 'bg-emerald-600 text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-emerald-600' }}
                   transition-all duration-200 hover:translate-x-1 active:translate-x-0 active:scale-[0.99] font-semibold">
            <span class="flex items-center gap-3">
                <span class="inline-flex w-9 h-9 rounded-lg {{ $isTrainingOpen ? 'bg-white/15' : 'bg-emerald-50' }} items-center justify-center">
                    <i class='bx bx-dumbbell text-lg {{ $isTrainingOpen ? 'text-white' : 'text-emerald-600' }}'></i>
                </span>
                <span class="sidebar-text">Training Mgmt</span>
            </span>
            <svg id="training-arrow" class="w-4 h-4 {{ $isTrainingOpen ? 'text-white rotate-180' : 'text-emerald-400' }} transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div id="training-submenu" class="submenu {{ $isTrainingOpen ? 'is-open' : '' }} mt-1">
            <div class="pl-4 pr-2 py-2 space-y-1 border-l-2 border-gray-100 ml-6">
                <a href="{{ route('training.catalog.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('training.catalog.*') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Training Catalog
                </a>
                <a href="{{ route('training.assign.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('training.assign.*') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Assign Training
                </a>
                <a href="{{ route('training.grant-request.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('training.grant-request.*') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Grant Request
                </a>
                <a href="{{ route('training.room.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('training.room.*') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Training Room
                </a>
            </div>
        </div>

        <!-- LEARNING Section -->
        <div class="text-xs font-bold text-gray-400 tracking-wider px-2 mt-6">LEARNING & ASSESSMENT</div>

        <!-- Learning Management Dropdown -->
        <button id="learning-menu-btn"
            class="mt-3 w-full flex items-center justify-between px-4 py-3 rounded-xl
                   {{ $isLearningOpen ? 'bg-emerald-600 text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-emerald-600' }}
                   transition-all duration-200 hover:translate-x-1 active:translate-x-0 active:scale-[0.99] font-semibold">
            <span class="flex items-center gap-3">
                <span class="inline-flex w-9 h-9 rounded-lg {{ $isLearningOpen ? 'bg-white/15' : 'bg-emerald-50' }} items-center justify-center">
                    <i class='bx bx-book-open text-lg {{ $isLearningOpen ? 'text-white' : 'text-emerald-600' }}'></i>
                </span>
                <span class="sidebar-text">Learning Mgmt</span>
            </span>
            <svg id="learning-arrow" class="w-4 h-4 {{ $isLearningOpen ? 'text-white rotate-180' : 'text-emerald-400' }} transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div id="learning-submenu" class="submenu {{ $isLearningOpen ? 'is-open' : '' }} mt-1">
            <div class="pl-4 pr-2 py-2 space-y-1 border-l-2 border-gray-100 ml-6">
                <a href="{{ route('learning.assessment') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('learning.assessment') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Assessment Center
                </a>
                <a href="{{ route('learning.hub') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('learning.hub') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Assessment Hub
                </a>
                <a href="{{ route('learning.self-assess') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('learning.self-assess') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Self Assessment
                </a>
                <a href="{{ route('assessment.results') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('assessment.results*') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Assessment Results
                </a>
            </div>
        </div>

        <!-- SYSTEM ADMIN Section -->
        <div class="text-xs font-bold text-gray-400 tracking-wider px-2 mt-6">SYSTEM ADMIN</div>

        <!-- Succession Planning Dropdown -->
        <button id="succession-menu-btn"
            class="mt-3 w-full flex items-center justify-between px-4 py-3 rounded-xl
                   {{ $isSuccessionOpen ? 'bg-emerald-600 text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-emerald-600' }}
                   transition-all duration-200 hover:translate-x-1 active:translate-x-0 active:scale-[0.99] font-semibold">
            <span class="flex items-center gap-3">
                <span class="inline-flex w-9 h-9 rounded-lg {{ $isSuccessionOpen ? 'bg-white/15' : 'bg-emerald-50' }} items-center justify-center">
                    <i class='bx bx-git-branch text-lg {{ $isSuccessionOpen ? 'text-white' : 'text-emerald-600' }}'></i>
                </span>
                <span class="sidebar-text">Succession Planning</span>
            </span>
            <svg id="succession-arrow" class="w-4 h-4 {{ $isSuccessionOpen ? 'text-white rotate-180' : 'text-emerald-400' }} transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div id="succession-submenu" class="submenu {{ $isSuccessionOpen ? 'is-open' : '' }} mt-1">
            <div class="pl-4 pr-2 py-2 space-y-1 border-l-2 border-gray-100 ml-6">
                <a href="{{ route('succession.successors') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('succession.successors') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Successors
                </a>
                <a href="{{ route('succession.potential-successors') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('succession.potential-successors') ? 'text-emerald-600 bg-green-50 font-medium' : 'text-gray-600 hover:bg-green-50 hover:text-emerald-600' }} transition-all duration-200 hover:translate-x-1">
                    Talent Pool
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 px-2">
            <div class="flex items-center gap-2 text-xs font-bold text-emerald-600">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                SYSTEM ONLINE
            </div>
            <div class="text-[11px] text-gray-400 mt-2 leading-snug">
                Microfinance HR © 2026<br/>
                Human Resource II System
            </div>
        </div>
    </div>
</aside>

<style>
    /* Submenu animation */
    .submenu {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.20s ease-out;
    }

    .submenu.is-open {
        max-height: 260px;
        opacity: 1;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.20s ease-in;
    }

    /* Sidebar scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #sidebar {
            transform: translateX(-100%);
        }
        #sidebar.open {
            transform: translateX(0);
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown setup function
    function setupDropdown(btnId, submenuId, arrowId) {
        const btn = document.getElementById(btnId);
        const submenu = document.getElementById(submenuId);
        const arrow = document.getElementById(arrowId);

        if (!btn || !submenu || !arrow) return;

        // Set initial state if already open
        if (submenu.classList.contains('is-open')) {
            arrow.classList.add('rotate-180');
        }

        btn.addEventListener('click', () => {
            submenu.classList.toggle('is-open');
            arrow.classList.toggle('rotate-180');
        });
    }

    // Initialize all dropdowns
    setupDropdown('competency-menu-btn', 'competency-submenu', 'competency-arrow');
    setupDropdown('training-menu-btn', 'training-submenu', 'training-arrow');
    setupDropdown('learning-menu-btn', 'learning-submenu', 'learning-arrow');
    setupDropdown('succession-menu-btn', 'succession-submenu', 'succession-arrow');
});
</script>
