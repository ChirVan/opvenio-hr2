<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    {{-- Print Styles --}}
    <style>
        @media print {
            /* Hide navbar, sidebar, overlay, and buttons */
            #navbar, #sidebar, #sidebarOverlay, #exportBtn, #syncForm, .no-print {
                display: none !important;
            }
            
            /* Reset main content positioning */
            #mainContent {
                margin-left: 0 !important;
                margin-top: 0 !important;
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
            }
            
            /* Remove shadows and adjust backgrounds for print */
            .shadow, .shadow-lg, .shadow-sm {
                box-shadow: none !important;
            }
            
            /* Ensure content fits on page */
            .bg-gradient-to-r {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Page settings */
            @page {
                margin: 1cm;
                size: A4 landscape;
            }
            
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Make grid items break properly */
            .grid {
                display: block !important;
            }
            
            .grid > div {
                page-break-inside: avoid;
                margin-bottom: 1rem;
            }
            
            /* Print header */
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 2px solid #059669;
            }
        }
        
        /* Hide print header on screen */
        .print-header {
            display: none;
        }
    </style>

    @php
        // Fetch employee data from the controller
        $totalEmployees = $totalEmployees ?? 0;
        $activeEmployees = $activeEmployees ?? 0;
        $recentHires = $recentHires ?? [];
    @endphp

    <div class="py-3" id="printableContent">
        <!-- Print Header (only visible when printing) -->
        <div class="print-header">
            <h1 class="text-2xl font-bold text-gray-900">Microfinance HR - Dashboard Report</h1>
            <p class="text-sm text-gray-600">Human Resource II System | Generated: {{ now()->format('F d, Y - h:i A') }}</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif

        <!-- Dashboard Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600 mt-1">Welcome back! Here's what's happening with your HR system.</p>
            </div>

            <div class="flex items-center gap-3">
                {{-- Export/Print Button --}}
                <button id="exportBtn" type="button" 
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg shadow transition flex items-center gap-2">
                    <i class='bx bx-printer text-lg'></i>
                    <span class="hidden sm:inline">Export</span>
                </button>

                {{-- Sync with HR4 Button --}}
                <form id="syncForm" method="POST" action="javascript:void(0);">
                    @csrf
                    <button id="syncBtn" type="button" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow transition flex items-center gap-2">
                        <i class='bx bx-refresh text-lg'></i>
                        <span class="hidden sm:inline">Sync HR4</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Courses Card -->
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-yellow-100 text-sm font-medium">Total Courses</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($totalCourses ?? 0) }}</p>
                        <p class="text-yellow-100 text-sm mt-1">Available Materials: {{ number_format($availableCourses ?? 0) }}</p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3 flex items-center justify-center">
                        <i class='bx bx-book-open text-yellow-700 text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Assigned Employee Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-blue-100 text-sm font-medium">Assigned Employee</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($assignedEmployees ?? 0) }}</p>
                        <p class="text-blue-100 text-sm mt-1">Employees with assignments</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3 flex items-center justify-center">
                        <i class='bx bx-user-check text-blue-700 text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Identified Successors Card -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-green-100 text-sm font-medium">Identified Successors</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($identifiedSuccessors ?? 0) }}</p>
                        <p class="text-green-100 text-sm mt-1">Talent pool</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3 flex items-center justify-center">
                        <i class='bx bx-group text-green-700 text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Re-evaluation Employee Card -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-red-100 text-sm font-medium">Re-evaluation Employee</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($reevaluationEmployees ?? 0) }}</p>
                        <p class="text-red-100 text-sm mt-1">Pending review</p>
                    </div>
                    <div class="bg-red-400 bg-opacity-30 rounded-full p-3 flex items-center justify-center">
                        <i class='bx bx-error text-red-700 text-3xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Potential Successors - Modern Graph View -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-4 sm:px-6 py-3 sm:py-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-0">
                        <h3 class="text-base sm:text-lg font-semibold text-white">Potential Successors</h3>
                        <span class="bg-white text-emerald-600 text-xs sm:text-sm font-bold px-2 sm:px-3 py-1 rounded-full shadow">
                            <span class="text-base sm:text-lg">{{ count($approvedEmployees ?? []) }}</span> Total
                        </span>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    @php
                        // Calculate status distribution
                        $statusCounts = collect($approvedEmployees ?? [])->groupBy('status')->map->count();
                        $passedCount = $statusCounts->get('passed', 0);
                        $pendingCount = $statusCounts->get('pending', 0);
                        $reviewCount = $statusCounts->get('review', 0);
                        $otherCount = collect($approvedEmployees ?? [])->count() - $passedCount - $pendingCount - $reviewCount;
                        
                        // Group by position/job title for chart
                        $positionCounts = collect($approvedEmployees ?? [])->groupBy('job_title')->map->count()->take(6);
                    @endphp

                    <!-- Chart Container -->
                    <div class="relative mb-4 w-full" style="min-height: 200px;">
                        <canvas id="successorsChart"></canvas>
                    </div>

                    <!-- Top Successors List (Compact) -->
                    <div class="border-t pt-3 sm:pt-4">
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-700 mb-2 sm:mb-3 flex items-center">
                            <i class='bx bx-trophy text-yellow-500 mr-2'></i>
                            Top Candidates
                        </h4>
                        <div class="space-y-2 max-h-40 sm:max-h-48 overflow-y-auto">
                            @forelse (collect($approvedEmployees)->take(5) as $employee)
                                <div class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
                                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                            {{ strtoupper(substr($employee->full_name ?? 'N', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ $employee->full_name }}</div>
                                            <div class="text-xs text-gray-500 truncate hidden sm:block">{{ $employee->job_title }}</div>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold flex-shrink-0 ml-2 {{ $employee->status == 'passed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">
                                    <i class='bx bx-user-x text-3xl text-gray-300'></i>
                                    <p class="text-sm mt-2">No successors identified yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skill Gap Analysis Metrics -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-3 sm:px-4 py-2 sm:py-3">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-1 sm:gap-0">
                        <h3 class="text-sm sm:text-base font-semibold text-white">Skill Gap Analysis</h3>
                        
                    </div>
                </div>
                <div class="p-3 sm:p-4">
                    @php
                        // Skill gap metrics - can be replaced with real data from controller
                        $skillGapData = [
                            'critical' => $criticalGaps ?? 8,
                            'moderate' => $moderateGaps ?? 15,
                            'minor' => $minorGaps ?? 12,
                            'meets' => $meetsExpectation ?? 25,
                            'exceeds' => $exceedsExpectation ?? 10
                        ];
                        $totalAssessed = array_sum($skillGapData);
                    @endphp

                    <!-- Chart Container -->
                    <div class="relative mb-3 w-full" style="min-height: 160px;">
                        <canvas id="skillGapChart"></canvas>
                    </div>

                    <!-- Gap Summary Cards -->
                    <div class="border-t pt-2 sm:pt-3">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                            <i class='bx bx-bar-chart-alt-2 text-purple-500 mr-1 text-sm'></i>
                            Gap Distribution
                        </h4>
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-1.5 sm:gap-2">
                            <div class="bg-red-50 rounded-md p-1.5 sm:p-2 border border-red-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] text-red-600 font-medium">Critical</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                </div>
                                <p class="text-sm sm:text-base font-bold text-red-700">{{ $skillGapData['critical'] }}</p>
                                <p class="text-[9px] text-red-500">{{ $totalAssessed > 0 ? round(($skillGapData['critical'] / $totalAssessed) * 100) : 0 }}%</p>
                            </div>
                            <div class="bg-orange-50 rounded-md p-1.5 sm:p-2 border border-orange-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] text-orange-600 font-medium">Moderate</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                </div>
                                <p class="text-sm sm:text-base font-bold text-orange-700">{{ $skillGapData['moderate'] }}</p>
                                <p class="text-[9px] text-orange-500">{{ $totalAssessed > 0 ? round(($skillGapData['moderate'] / $totalAssessed) * 100) : 0 }}%</p>
                            </div>
                            <div class="bg-yellow-50 rounded-md p-1.5 sm:p-2 border border-yellow-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] text-yellow-600 font-medium">Minor</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                </div>
                                <p class="text-sm sm:text-base font-bold text-yellow-700">{{ $skillGapData['minor'] }}</p>
                                <p class="text-[9px] text-yellow-500">{{ $totalAssessed > 0 ? round(($skillGapData['minor'] / $totalAssessed) * 100) : 0 }}%</p>
                            </div>
                            <div class="bg-green-50 rounded-md p-1.5 sm:p-2 border border-green-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] text-green-600 font-medium">Meets</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                </div>
                                <p class="text-sm sm:text-base font-bold text-green-700">{{ $skillGapData['meets'] }}</p>
                                <p class="text-[9px] text-green-500">{{ $totalAssessed > 0 ? round(($skillGapData['meets'] / $totalAssessed) * 100) : 0 }}%</p>
                            </div>
                            <div class="bg-blue-50 rounded-md p-1.5 sm:p-2 border border-blue-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] text-blue-600 font-medium">Exceeds</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                </div>
                                <p class="text-sm sm:text-base font-bold text-blue-700">{{ $skillGapData['exceeds'] }}</p>
                                <p class="text-[9px] text-blue-500">{{ $totalAssessed > 0 ? round(($skillGapData['exceeds'] / $totalAssessed) * 100) : 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Export/Print functionality
document.getElementById('exportBtn').addEventListener('click', function() {
    window.print();
});

// Detect mobile for responsive settings
const isMobile = window.innerWidth < 640;

// Skill Gap Analysis Pie Chart
const skillGapCtx = document.getElementById('skillGapChart');
if (skillGapCtx) {
    new Chart(skillGapCtx, {
        type: 'doughnut',
        data: {
            labels: ['Critical', 'Moderate', 'Minor', 'Meets', 'Exceeds'],
            datasets: [{
                data: [
                    {{ $skillGapData['critical'] ?? 8 }},
                    {{ $skillGapData['moderate'] ?? 15 }},
                    {{ $skillGapData['minor'] ?? 12 }},
                    {{ $skillGapData['meets'] ?? 25 }},
                    {{ $skillGapData['exceeds'] ?? 10 }}
                ],
                backgroundColor: [
                    'rgba(239, 68, 68, 0.85)',
                    'rgba(249, 115, 22, 0.85)',
                    'rgba(234, 179, 8, 0.85)',
                    'rgba(34, 197, 94, 0.85)',
                    'rgba(59, 130, 246, 0.85)'
                ],
                borderColor: [
                    'rgba(239, 68, 68, 1)',
                    'rgba(249, 115, 22, 1)',
                    'rgba(234, 179, 8, 1)',
                    'rgba(34, 197, 94, 1)',
                    'rgba(59, 130, 246, 1)'
                ],
                borderWidth: 1,
                hoverOffset: 6,
                hoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '55%',
            plugins: {
                legend: {
                    position: 'right',
                    align: 'center',
                    labels: {
                        padding: 6,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 6,
                        font: {
                            size: isMobile ? 8 : 9,
                            weight: '500'
                        },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                            return data.labels.map((label, i) => {
                                const value = data.datasets[0].data[i];
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return {
                                    text: `${label} ${percentage}%`,
                                    fillStyle: data.datasets[0].backgroundColor[i],
                                    strokeStyle: data.datasets[0].borderColor[i],
                                    lineWidth: 1,
                                    hidden: false,
                                    index: i,
                                    pointStyle: 'circle'
                                };
                            });
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    padding: 8,
                    titleFont: { size: 10, weight: 'bold' },
                    bodyFont: { size: 9 },
                    cornerRadius: 6,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                            return ` ${context.raw} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Successors Line Chart - Trend Over Time
const successorsCtx = document.getElementById('successorsChart');
if (successorsCtx) {
    // Generate sample monthly data (replace with real data from backend)
    const months = ['Aug 2025', 'Sep 2025', 'Oct 2025', 'Nov 2025', 'Dec 2025', 'Jan 2026', 'Feb 2026'];
    const passedData = [{{ max(0, ($passedCount ?? 3) - 5) }}, {{ max(0, ($passedCount ?? 3) - 4) }}, {{ max(0, ($passedCount ?? 3) - 3) }}, {{ max(0, ($passedCount ?? 3) - 2) }}, {{ max(0, ($passedCount ?? 3) - 1) }}, {{ $passedCount ?? 3 }}, {{ ($passedCount ?? 3) + 1 }}];
    const pendingData = [{{ ($pendingCount ?? 2) + 3 }}, {{ ($pendingCount ?? 2) + 2 }}, {{ ($pendingCount ?? 2) + 2 }}, {{ ($pendingCount ?? 2) + 1 }}, {{ ($pendingCount ?? 2) + 1 }}, {{ $pendingCount ?? 2 }}, {{ max(0, ($pendingCount ?? 2) - 1) }}];
    const totalData = passedData.map((val, idx) => val + pendingData[idx]);
    
    new Chart(successorsCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Total Successors',
                    data: totalData,
                    borderColor: 'rgba(99, 102, 241, 1)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: isMobile ? 2 : 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: isMobile ? 1 : 2,
                    pointRadius: isMobile ? 3 : 5,
                    pointHoverRadius: isMobile ? 5 : 7
                },
                {
                    label: 'Passed',
                    data: passedData,
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: isMobile ? 1.5 : 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: isMobile ? 1 : 2,
                    pointRadius: isMobile ? 2 : 4,
                    pointHoverRadius: isMobile ? 4 : 6
                },
                {
                    label: 'Pending',
                    data: pendingData,
                    borderColor: 'rgba(245, 158, 11, 1)',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: isMobile ? 1.5 : 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(245, 158, 11, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: isMobile ? 1 : 2,
                    pointRadius: isMobile ? 2 : 4,
                    pointHoverRadius: isMobile ? 4 : 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: isMobile ? 'bottom' : 'top',
                    align: isMobile ? 'center' : 'end',
                    labels: {
                        padding: isMobile ? 10 : 15,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: isMobile ? 6 : 8,
                        font: {
                            size: isMobile ? 10 : 11,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    padding: isMobile ? 8 : 12,
                    titleFont: { size: isMobile ? 11 : 13, weight: 'bold' },
                    bodyFont: { size: isMobile ? 10 : 12 },
                    cornerRadius: 8,
                    displayColors: true,
                    boxPadding: 4
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: isMobile ? 9 : 11
                        },
                        maxRotation: isMobile ? 45 : 0,
                        minRotation: isMobile ? 45 : 0
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: isMobile ? 9 : 11
                        },
                        stepSize: 1
                    }
                }
            }
        }
    });
}

document.getElementById('syncBtn').addEventListener('click', async () => {
    // SweetAlert2 confirmation dialog
    const confirmResult = await Swal.fire({
        title: 'Sync with HR4?',
        text: 'This will update or create employee accounts from HR4 system.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4F46E5',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="bx bx-refresh mr-1"></i> Yes, Sync Now',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    });

    if (!confirmResult.isConfirmed) return;

    // Use AbortController to guard against calls hanging
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 30000); // 30s client-side timeout

    // Disable button and show loading state
    const btn = document.getElementById('syncBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-1"></i> Syncing...';

    // Show loading toast
    Swal.fire({
        title: 'Syncing...',
        html: 'Please wait while we sync employees from HR4',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        const res = await fetch('{{ route("sync.employees.hr4") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            signal: controller.signal,
        });

        clearTimeout(timeout);

        const data = await res.json();

        if (!res.ok) {
            Swal.fire({
                icon: 'error',
                title: 'Sync Failed',
                html: `<p class="text-gray-600">Status: ${res.status}</p><p class="text-sm text-red-600 mt-2">${data.message || JSON.stringify(data)}</p>`,
                confirmButtonColor: '#EF4444'
            });
            return;
        }

        if (!data.success) {
            Swal.fire({
                icon: 'error',
                title: 'Sync Failed',
                text: data.message || 'Unknown error occurred',
                confirmButtonColor: '#EF4444'
            });
            return;
        }

        // Build success message with details
        let htmlContent = '';
        if (data.no_changes) {
            htmlContent = `
                <div class="text-center">
                    <p class="text-green-600 font-semibold">No changes needed</p>
                    <p class="text-gray-500 text-sm mt-1">Database is already synchronized</p>
                </div>
            `;
        } else {
            htmlContent = `
                <div class="grid grid-cols-2 gap-3 text-sm mt-2">
                    <div class="bg-green-50 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-green-600">${data.created || 0}</p>
                        <p class="text-green-700">Created</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-blue-600">${data.updated || 0}</p>
                        <p class="text-blue-700">Updated</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-yellow-600">${data.skipped || 0}</p>
                        <p class="text-yellow-700">Skipped</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-red-600">${data.errors || 0}</p>
                        <p class="text-red-700">Errors</p>
                    </div>
                </div>
            `;
        }

        // Show error details if any
        if (data.error_details && data.error_details.length > 0) {
            htmlContent += `
                <div class="mt-4 p-3 bg-red-50 rounded-lg text-left">
                    <p class="text-sm font-semibold text-red-700 mb-2">Error Details:</p>
                    <ul class="text-xs text-red-600 space-y-1 max-h-32 overflow-y-auto">
                        ${data.error_details.slice(0, 5).map(err => `<li>• ${err}</li>`).join('')}
                        ${data.error_details.length > 5 ? `<li class="text-red-500 font-medium">... and ${data.error_details.length - 5} more errors (check logs)</li>` : ''}
                    </ul>
                </div>
            `;
        }

        await Swal.fire({
            icon: 'success',
            title: 'Sync Complete!',
            html: htmlContent,
            confirmButtonColor: '#10B981',
            confirmButtonText: 'Done'
        });

        window.location.href = '/dashboard';

    } catch (err) {
        if (err.name === 'AbortError') {
            Swal.fire({
                icon: 'warning',
                title: 'Sync Timed Out',
                text: 'The request took too long. Please try again.',
                confirmButtonColor: '#F59E0B'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Sync Error',
                text: err.message || 'An unknown error occurred',
                confirmButtonColor: '#EF4444'
            });
        }
    } finally {
        // Restore button
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});
</script>
</x-app-layout>
