<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

<div class="container-fluid px-4 py-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class='bx bx-calendar-event text-emerald-600'></i>
                Request Seminar Session
            </h1>
            <p class="text-gray-500 mt-1">Schedule training sessions for seminars</p>
        </div>
        <button onclick="openNewBookingModal()" class="mt-4 md:mt-0 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
            <i class='bx bx-plus-circle'></i>
            New Room Booking
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="totalBookings">0</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                    <i class='bx bx-calendar text-2xl text-emerald-600'></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Upcoming Sessions</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="upcomingSessions">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class='bx bx-time text-2xl text-blue-600'></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Attendees</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="totalAttendees">0</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class='bx bx-group text-2xl text-purple-600'></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Available Courses</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_courses'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class='bx bx-book-content text-2xl text-orange-600'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Bookings Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-50 to-green-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class='bx bx-list-ul mr-2 text-emerald-600'></i>
                        Room Booking Requests
                    </h2>
                    <p class="text-xs text-gray-600 mt-1">Manage training session bookings and attendees</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-wrap items-center gap-3">
                <input type="text" id="searchBooking" placeholder="Search by title or training course..." 
                       class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-400 flex-1 min-w-[200px]">
                
                <select id="filterStatus" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-400">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <select id="filterCourse" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-400">
                    <option value="">All Courses</option>
                    @foreach($catalogs as $catalog)
                        <option value="{{ $catalog->id }}">{{ $catalog->title }}</option>
                    @endforeach
                </select>

                <button onclick="refreshBookings()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class='bx bx-refresh mr-1'></i> Refresh
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider">Session Details</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider">Training Course</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Schedule</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Attendees</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="bookingsTableBody" class="bg-white divide-y divide-gray-100">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class='bx bx-calendar-x text-5xl text-gray-300 mb-3'></i>
                                <p class="text-gray-500 font-medium">No room bookings yet</p>
                                <p class="text-sm text-gray-400 mt-1">Click "New Room Booking" to schedule a training session</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span id="showingFrom">0</span> to <span id="showingTo">0</span> of <span id="totalRecords">0</span> results
            </div>
            <div class="flex items-center gap-2">
                <select id="rowsPerPage" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-400">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
                <div id="paginationButtons" class="flex gap-1"></div>
            </div>
        </div>
    </div>
</div>

<!-- New Booking Modal -->
<div id="newBookingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen p-4 pt-10">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full my-8">
            <div class="bg-gradient-to-r from-emerald-500 to-green-500 px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class='bx bx-calendar-plus mr-2'></i>
                    New Training Room Booking
                </h3>
                <p class="text-emerald-100 text-sm mt-1">Schedule a training session and add multiple attendees</p>
            </div>
            
            <div class="p-6">
                <!-- Step Indicator -->
                <div class="flex items-center justify-center mb-6">
                    <div class="flex items-center">
                        <div id="step1Indicator" class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-semibold">1</div>
                        <span class="ml-2 text-sm font-medium text-gray-700">Session Details</span>
                    </div>
                    <div class="w-16 h-1 bg-gray-200 mx-4">
                        <div id="stepProgress" class="h-full bg-emerald-600 transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <div class="flex items-center">
                        <div id="step2Indicator" class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold">2</div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Select Attendees</span>
                    </div>
                </div>

                <!-- Step 1: Session Details -->
                <div id="step1Content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Training Course <span class="text-red-500">*</span></label>
                            <select id="trainingCourse" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                                <option value="">-- Select Training Course --</option>
                                @foreach($catalogs as $catalog)
                                    <option value="{{ $catalog->id }}" data-materials="{{ $catalog->materials->count() }}">
                                        {{ $catalog->title }} ({{ $catalog->materials->count() }} materials)
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Note: Attendees may have different training plans - they will all learn the selected course in this session</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Session Date <span class="text-red-500">*</span></label>
                            <input type="date" id="sessionDate" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" id="roomLocation" value="Training Room" disabled class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 text-gray-600 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                            <input type="time" id="startTime" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                            <input type="time" id="endTime" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Facilitator/Trainer</label>
                            <input type="text" id="facilitator" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="e.g., John Smith, HR Training Specialist">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea id="sessionNotes" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="Any additional notes for this session..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Select Attendees -->
                <div id="step2Content" class="hidden">
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select Attendees</label>
                                <p class="text-xs text-gray-500">These employees will attend this training session together</p>
                            </div>
                            <span class="text-sm text-emerald-600 font-medium"><span id="selectedAttendeesCount">0</span> selected</span>
                        </div>
                        <input type="text" id="searchAttendees" placeholder="Search employees by name, ID, or job title..." 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-400 mb-3">
                        
                        <!-- Quick Select Options -->
                        <div class="flex flex-wrap gap-2 mb-3">
                            <button type="button" onclick="selectAllEmployees()" class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium hover:bg-emerald-200 transition-colors">
                                <i class='bx bx-check-double mr-1'></i> Select All
                            </button>
                            <button type="button" onclick="clearAllSelection()" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-xs font-medium hover:bg-gray-200 transition-colors">
                                <i class='bx bx-x mr-1'></i> Clear All
                            </button>
                        </div>
                    </div>

                    <!-- Employee List -->
                    <div id="employeeListContainer" class="border border-gray-200 rounded-lg max-h-[350px] overflow-y-auto">
                        <div class="p-4 text-center text-gray-500">
                            <i class='bx bx-loader-alt bx-spin text-2xl'></i>
                            <p class="text-sm mt-2">Loading employees...</p>
                        </div>
                    </div>

                    <!-- Selected Attendees Summary -->
                    <div id="selectedAttendeesSummary" class="mt-4 p-3 bg-emerald-50 rounded-lg hidden">
                        <p class="text-sm font-medium text-emerald-700 mb-2">Selected Attendees:</p>
                        <div id="selectedAttendeesList" class="flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-between">
                <button id="backBtn" type="button" onclick="goToStep(1)" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition-colors hidden">
                    <i class='bx bx-arrow-back mr-1'></i> Back
                </button>
                <div class="flex gap-3 ml-auto">
                    <button type="button" onclick="closeNewBookingModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                        Cancel
                    </button>
                    <button id="nextBtn" type="button" onclick="goToStep(2)" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Next: Select Attendees <i class='bx bx-arrow-right ml-1'></i>
                    </button>
                    <button id="submitBtn" type="button" onclick="submitBooking()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors hidden">
                        <i class='bx bx-check mr-1'></i> Create Booking
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Booking Modal -->
<div id="viewBookingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen p-4 pt-10">
        <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full my-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class='bx bx-calendar-check mr-2'></i>
                    <span id="viewBookingTitle">Booking Details</span>
                </h3>
            </div>
            <div class="p-6" id="viewBookingContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-end">
                <button type="button" onclick="closeViewBookingModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let employeeData = [];
    let bookingsData = [];
    let selectedAttendees = new Set();
    let currentStep = 1;
    let currentPage = 1;
    let itemsPerPage = 10;

    document.addEventListener('DOMContentLoaded', function() {
        loadEmployees();
        loadBookings();

        // Set minimum date to today
        document.getElementById('sessionDate').min = new Date().toISOString().split('T')[0];

        // Search attendees
        document.getElementById('searchAttendees').addEventListener('input', filterEmployeeList);
        document.getElementById('searchBooking').addEventListener('input', filterBookings);
        document.getElementById('filterStatus').addEventListener('change', filterBookings);
        document.getElementById('filterCourse').addEventListener('change', filterBookings);
    });

    function loadEmployees() {
        fetch('{{ route("training.assign.api-employees") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.employees) {
                    employeeData = data.employees.map(emp => {
                        let empId = emp.employee_id || emp.id || '';
                        if (typeof empId === 'object' && empId !== null) {
                            empId = empId.employee_id || empId.id || 'N/A';
                        }
                        empId = String(empId || 'N/A');

                        let empName = emp.full_name || emp.name || '';
                        if (!empName && emp.first_name) {
                            empName = `${emp.first_name || ''} ${emp.last_name || ''}`;
                        }
                        empName = String(empName || '').trim() || 'Unknown';

                        let empEmail = emp.email || emp.work_email || '';
                        empEmail = String(empEmail || '');

                        // Handle job title - check nested job object first
                        let jobTitle = 'N/A';
                        if (emp.job && emp.job.job_title) {
                            jobTitle = emp.job.job_title;
                        } else if (emp.job_title) {
                            jobTitle = emp.job_title;
                        } else if (emp.position && emp.position.department) {
                            jobTitle = emp.position.department;
                        }
                        jobTitle = String(jobTitle || 'N/A');

                        // Get department from position if available
                        let department = 'N/A';
                        if (emp.position && emp.position.department) {
                            department = emp.position.department;
                        }

                        return {
                            id: empId,
                            employee_id: empId,
                            name: empName,
                            email: empEmail,
                            job_title: jobTitle,
                            department: department
                        };
                    });
                    
                    renderEmployeeList();
                }
            })
            .catch(error => {
                console.error('Error loading employees:', error);
            });
    }

    function loadBookings() {
        // Show loading state
        document.getElementById('bookingsTableBody').innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class='bx bx-loader-alt bx-spin text-4xl text-gray-400 mb-2'></i>
                        <p class="text-sm text-gray-500">Loading bookings...</p>
                    </div>
                </td>
            </tr>
        `;

        // Load bookings from API
        fetch('{{ route("training.room.bookings") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bookingsData = data.bookings || [];
                    renderBookingsTable();
                    
                    // Update stats from API
                    if (data.stats) {
                        document.getElementById('totalBookings').textContent = data.stats.total || 0;
                        document.getElementById('upcomingSessions').textContent = data.stats.upcoming || 0;
                        document.getElementById('totalAttendees').textContent = data.stats.totalAttendees || 0;
                    }
                } else {
                    console.error('Error loading bookings:', data.error);
                    bookingsData = [];
                    renderBookingsTable();
                }
            })
            .catch(error => {
                console.error('Error loading bookings:', error);
                bookingsData = [];
                renderBookingsTable();
            });
    }

    function updateStats() {
        // Stats are now updated from API response in loadBookings()
        document.getElementById('totalBookings').textContent = bookingsData.length;
        
        const today = new Date().toISOString().split('T')[0];
        const upcoming = bookingsData.filter(b => b.sessionDate >= today && b.status !== 'cancelled').length;
        document.getElementById('upcomingSessions').textContent = upcoming;

        const totalAttendees = bookingsData.reduce((sum, b) => sum + (b.attendees ? b.attendees.length : 0), 0);
        document.getElementById('totalAttendees').textContent = totalAttendees;
    }

    function renderBookingsTable(filteredData = null) {
        const data = filteredData || bookingsData;
        const tbody = document.getElementById('bookingsTableBody');

        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-calendar-x text-5xl text-gray-300 mb-3'></i>
                            <p class="text-gray-500 font-medium">No room bookings yet</p>
                            <p class="text-sm text-gray-400 mt-1">Click "New Room Booking" to schedule a training session</p>
                        </div>
                    </td>
                </tr>
            `;
            updatePaginationInfo(0, 0, 0);
            return;
        }

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, data.length);
        const paginatedData = data.slice(startIndex, endIndex);

        tbody.innerHTML = paginatedData.map(booking => {
            const statusBadge = getStatusBadge(booking.status);
            const formattedDate = new Date(booking.sessionDate).toLocaleDateString('en-US', { 
                weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' 
            });
            const timeRange = booking.startTime && booking.endTime 
                ? `${formatTime(booking.startTime)} - ${formatTime(booking.endTime)}` 
                : 'Time not set';

            return `
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg flex items-center justify-center text-white">
                                <i class='bx bx-calendar-event text-xl'></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-900">${booking.title}</div>
                                <div class="text-xs text-gray-500"><i class='bx bx-map mr-1'></i>${booking.roomLocation || 'Location TBD'}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 font-medium">${booking.courseName || 'N/A'}</div>
                        <div class="text-xs text-gray-500">${booking.facilitator ? '<i class="bx bx-user mr-1"></i>' + booking.facilitator : ''}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-sm font-medium text-gray-900">${formattedDate}</div>
                        <div class="text-xs text-gray-500">${timeRange}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="viewBooking('${booking.id}')" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-700 hover:bg-purple-200 transition-colors">
                            <i class='bx bx-group mr-1'></i>${booking.attendees ? booking.attendees.length : 0}
                        </button>
                    </td>
                    <td class="px-6 py-4 text-center">${statusBadge}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="viewBooking('${booking.id}')" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                <i class='bx bx-show text-lg'></i>
                            </button>
                            <button onclick="editBookingStatus('${booking.id}')" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Update Status">
                                <i class='bx bx-edit text-lg'></i>
                            </button>
                            <button onclick="deleteBooking('${booking.id}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                <i class='bx bx-trash text-lg'></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        updatePaginationInfo(startIndex + 1, endIndex, data.length);
        renderPagination(data.length);
    }

    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700"><i class="bx bx-time-five mr-1"></i>Pending</span>',
            'approved': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><i class="bx bx-check mr-1"></i>Approved</span>',
            'rejected': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700"><i class="bx bx-x mr-1"></i>Rejected</span>',
            'ongoing': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"><i class="bx bx-play-circle mr-1"></i>Ongoing</span>',
            'completed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700"><i class="bx bx-check-circle mr-1"></i>Completed</span>',
            'cancelled': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700"><i class="bx bx-x-circle mr-1"></i>Cancelled</span>'
        };
        return badges[status] || badges['pending'];
    }

    function formatTime(time) {
        if (!time) return '';
        const [hours, minutes] = time.split(':');
        const h = parseInt(hours);
        const ampm = h >= 12 ? 'PM' : 'AM';
        const hour12 = h % 12 || 12;
        return `${hour12}:${minutes} ${ampm}`;
    }

    function updatePaginationInfo(from, to, total) {
        document.getElementById('showingFrom').textContent = total > 0 ? from : 0;
        document.getElementById('showingTo').textContent = to;
        document.getElementById('totalRecords').textContent = total;
    }

    function renderPagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const container = document.getElementById('paginationButtons');
        
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = '';
        for (let i = 1; i <= totalPages; i++) {
            html += `<button onclick="goToPage(${i})" class="px-3 py-1 ${i === currentPage ? 'bg-emerald-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'} border border-gray-300 rounded-md text-sm">${i}</button>`;
        }
        container.innerHTML = html;
    }

    function goToPage(page) {
        currentPage = page;
        renderBookingsTable();
    }

    // Modal Functions
    function openNewBookingModal() {
        document.getElementById('newBookingModal').classList.remove('hidden');
        currentStep = 1;
        updateStepUI();
        clearForm();
    }

    function closeNewBookingModal() {
        document.getElementById('newBookingModal').classList.add('hidden');
        clearForm();
    }

    function clearForm() {
        document.getElementById('trainingCourse').value = '';
        document.getElementById('sessionDate').value = '';
        document.getElementById('roomLocation').value = 'Training Room';
        document.getElementById('startTime').value = '';
        document.getElementById('endTime').value = '';
        document.getElementById('facilitator').value = '';
        document.getElementById('sessionNotes').value = '';
        selectedAttendees.clear();
        updateSelectedAttendeesUI();
    }

    function goToStep(step) {
        if (step === 2) {
            // Validate step 1
            const course = document.getElementById('trainingCourse').value;
            const date = document.getElementById('sessionDate').value;

            if (!course || !date) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in Training Course and Session Date.',
                    confirmButtonColor: '#10b981'
                });
                return;
            }
        }

        currentStep = step;
        updateStepUI();
    }

    function updateStepUI() {
        const step1 = document.getElementById('step1Content');
        const step2 = document.getElementById('step2Content');
        const step1Indicator = document.getElementById('step1Indicator');
        const step2Indicator = document.getElementById('step2Indicator');
        const stepProgress = document.getElementById('stepProgress');
        const backBtn = document.getElementById('backBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        if (currentStep === 1) {
            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            step1Indicator.className = 'w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-semibold';
            step2Indicator.className = 'w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold';
            stepProgress.style.width = '0%';
            backBtn.classList.add('hidden');
            nextBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
        } else {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            step1Indicator.className = 'w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-semibold';
            step2Indicator.className = 'w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-semibold';
            stepProgress.style.width = '100%';
            backBtn.classList.remove('hidden');
            nextBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
            renderEmployeeList();
        }
    }

    function renderEmployeeList(filteredData = null) {
        const data = filteredData || employeeData;
        const container = document.getElementById('employeeListContainer');

        if (data.length === 0) {
            container.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <i class='bx bx-search text-2xl'></i>
                    <p class="text-sm mt-2">No employees found</p>
                </div>
            `;
            return;
        }

        container.innerHTML = data.map(emp => `
            <div class="flex items-center p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors ${selectedAttendees.has(emp.employee_id) ? 'bg-emerald-50' : ''}" 
                 onclick="toggleAttendee('${emp.employee_id}')">
                <input type="checkbox" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 mr-3" 
                       ${selectedAttendees.has(emp.employee_id) ? 'checked' : ''} 
                       onclick="event.stopPropagation()">
                <div class="h-10 w-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                    ${emp.name.substring(0, 2).toUpperCase()}
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-900 truncate">${emp.name}</div>
                    <div class="text-xs text-gray-500 truncate">${emp.employee_id} • ${emp.job_title}</div>
                </div>
                <div class="ml-2 text-xs text-gray-400 hidden md:block">${emp.department}</div>
                ${selectedAttendees.has(emp.employee_id) ? '<i class="bx bx-check-circle text-emerald-600 text-xl ml-2"></i>' : ''}
            </div>
        `).join('');
    }

    function toggleAttendee(empId) {
        if (selectedAttendees.has(empId)) {
            selectedAttendees.delete(empId);
        } else {
            selectedAttendees.add(empId);
        }
        renderEmployeeList();
        updateSelectedAttendeesUI();
    }

    function selectAllEmployees() {
        employeeData.forEach(emp => selectedAttendees.add(emp.employee_id));
        renderEmployeeList();
        updateSelectedAttendeesUI();
    }

    function clearAllSelection() {
        selectedAttendees.clear();
        renderEmployeeList();
        updateSelectedAttendeesUI();
    }

    function updateSelectedAttendeesUI() {
        const count = selectedAttendees.size;
        document.getElementById('selectedAttendeesCount').textContent = count;

        const summary = document.getElementById('selectedAttendeesSummary');
        const list = document.getElementById('selectedAttendeesList');

        if (count > 0) {
            summary.classList.remove('hidden');
            const selectedEmps = employeeData.filter(e => selectedAttendees.has(e.employee_id));
            list.innerHTML = selectedEmps.slice(0, 10).map(emp => `
                <span class="inline-flex items-center px-2 py-1 bg-white rounded-full text-xs border border-emerald-200">
                    ${emp.name}
                    <button type="button" onclick="event.stopPropagation(); toggleAttendee('${emp.employee_id}')" class="ml-1 text-gray-400 hover:text-red-500">
                        <i class='bx bx-x'></i>
                    </button>
                </span>
            `).join('') + (count > 10 ? `<span class="text-xs text-gray-500">+${count - 10} more</span>` : '');
        } else {
            summary.classList.add('hidden');
        }
    }

    function filterEmployeeList() {
        const search = document.getElementById('searchAttendees').value.toLowerCase();
        if (!search) {
            renderEmployeeList();
            return;
        }

        const filtered = employeeData.filter(emp => 
            emp.name.toLowerCase().includes(search) ||
            emp.employee_id.toLowerCase().includes(search) ||
            emp.job_title.toLowerCase().includes(search) ||
            emp.department.toLowerCase().includes(search)
        );
        renderEmployeeList(filtered);
    }

    function filterBookings() {
        const search = document.getElementById('searchBooking').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;
        const course = document.getElementById('filterCourse').value;

        let filtered = bookingsData;

        if (search) {
            filtered = filtered.filter(b => 
                b.title.toLowerCase().includes(search) ||
                (b.courseName && b.courseName.toLowerCase().includes(search))
            );
        }

        if (status) {
            filtered = filtered.filter(b => b.status === status);
        }

        if (course) {
            filtered = filtered.filter(b => b.courseId === course);
        }

        currentPage = 1;
        renderBookingsTable(filtered);
    }

    function refreshBookings() {
        loadBookings();
        document.getElementById('searchBooking').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterCourse').value = '';
        
        Swal.fire({
            icon: 'success',
            title: 'Refreshed!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500
        });
    }

    function submitBooking() {
        if (selectedAttendees.size === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Attendees Selected',
                text: 'Please select at least one attendee for this training session.',
                confirmButtonColor: '#10b981'
            });
            return;
        }

        const courseSelect = document.getElementById('trainingCourse');
        const courseName = courseSelect.options[courseSelect.selectedIndex].text.split(' (')[0];

        // Prepare attendees data
        const attendeesData = Array.from(selectedAttendees).map(id => {
            const emp = employeeData.find(e => e.employee_id === id);
            return emp ? { id: emp.employee_id, name: emp.name, job_title: emp.job_title, department: emp.department } : { id, name: 'Unknown' };
        });

        // Show loading
        Swal.fire({
            title: 'Creating Booking...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        // Submit to API
        fetch('{{ route("training.room.bookings.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                training_catalog_id: document.getElementById('trainingCourse').value,
                course_name: courseName,
                session_date: document.getElementById('sessionDate').value,
                location: 'Training Room',
                start_time: document.getElementById('startTime').value || null,
                end_time: document.getElementById('endTime').value || null,
                facilitator: document.getElementById('facilitator').value.trim() || null,
                notes: document.getElementById('sessionNotes').value.trim() || null,
                attendees: attendeesData
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeNewBookingModal();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Booking Created!',
                    html: `
                        <div class="text-left">
                            <p class="text-gray-600 mb-2">Training session "<strong>${courseName}</strong>" has been scheduled.</p>
                            <p class="text-sm text-gray-500"><i class='bx bx-bookmark mr-1'></i>Booking Code: <strong>${data.booking.booking_code}</strong></p>
                            <p class="text-sm text-gray-500"><i class='bx bx-group mr-1'></i>${attendeesData.length} attendees will be notified.</p>
                            <p class="text-sm text-gray-500"><i class='bx bx-calendar mr-1'></i>${new Date(data.booking.sessionDate).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
                        </div>
                    `,
                    confirmButtonColor: '#10b981'
                });

                // Reload bookings from API
                loadBookings();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error || 'Failed to create booking',
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .catch(error => {
            console.error('Error creating booking:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to create booking. Please try again.',
                confirmButtonColor: '#ef4444'
            });
        });
    }

    function viewBooking(id) {
        const booking = bookingsData.find(b => b.id === id);
        if (!booking) return;

        document.getElementById('viewBookingTitle').textContent = booking.title;

        const formattedDate = new Date(booking.sessionDate).toLocaleDateString('en-US', { 
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
        });
        const timeRange = booking.startTime && booking.endTime 
            ? `${formatTime(booking.startTime)} - ${formatTime(booking.endTime)}` 
            : 'Time not set';

        document.getElementById('viewBookingContent').innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase font-medium">Training Course</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">${booking.courseName || 'N/A'}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase font-medium">Status</p>
                        <p class="mt-1">${getStatusBadge(booking.status)}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase font-medium">Date & Time</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">${formattedDate}</p>
                        <p class="text-xs text-gray-500">${timeRange}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase font-medium">Location</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">${booking.roomLocation || 'TBD'}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg col-span-2">
                        <p class="text-xs text-gray-500 uppercase font-medium">Facilitator</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">${booking.facilitator || 'Not assigned'}</p>
                    </div>
                </div>

                ${booking.notes ? `
                    <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                        <p class="text-xs text-yellow-600 uppercase font-medium">Notes</p>
                        <p class="text-sm text-gray-700 mt-1">${booking.notes}</p>
                    </div>
                ` : ''}

                <div>
                    <p class="text-sm font-semibold text-gray-800 mb-3">
                        <i class='bx bx-group mr-1'></i>Attendees (${booking.attendees ? booking.attendees.length : 0})
                    </p>
                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                        ${booking.attendees && booking.attendees.length > 0 ? booking.attendees.map((att, idx) => `
                            <div class="flex items-center p-3 border-b border-gray-100 last:border-b-0 ${idx % 2 === 0 ? 'bg-white' : 'bg-gray-50'}">
                                <div class="h-9 w-9 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                    ${att.name.substring(0, 2).toUpperCase()}
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">${att.name}</p>
                                    <p class="text-xs text-gray-500">${att.id} • ${att.job_title || 'N/A'}</p>
                                </div>
                                <div class="text-xs text-gray-400">${att.department || ''}</div>
                            </div>
                        `).join('') : '<p class="p-3 text-sm text-gray-500 text-center">No attendees</p>'}
                    </div>
                </div>
            </div>
        `;

        document.getElementById('viewBookingModal').classList.remove('hidden');
    }

    function closeViewBookingModal() {
        document.getElementById('viewBookingModal').classList.add('hidden');
    }

    function editBookingStatus(id) {
        const booking = bookingsData.find(b => b.id == id);
        if (!booking) return;

        Swal.fire({
            title: 'Update Booking Status',
            html: `<p class="text-sm text-gray-600 mb-3">${booking.title || booking.courseName}</p>`,
            input: 'select',
            inputOptions: {
                'pending': 'Pending',
                'approved': 'Approved',
                'rejected': 'Rejected',
                'ongoing': 'Ongoing',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            },
            inputValue: booking.status,
            showCancelButton: true,
            confirmButtonText: 'Update',
            confirmButtonColor: '#10b981',
            inputValidator: (value) => {
                if (!value) return 'Please select a status';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Update via API
                fetch(`{{ url('training/room/bookings') }}/${id}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: result.value })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadBookings(); // Reload from API
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error || 'Failed to update status'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update status'
                    });
                });
            }
        });
    }

    function deleteBooking(id) {
        const booking = bookingsData.find(b => b.id == id);
        if (!booking) return;

        Swal.fire({
            title: 'Delete Booking?',
            html: `<p class="text-gray-600">Are you sure you want to delete "<strong>${booking.title || booking.courseName}</strong>"?</p><p class="text-sm text-gray-500 mt-1">This action cannot be undone.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                // Delete via API
                fetch(`{{ url('training/room/bookings') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadBookings(); // Reload from API
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The booking has been removed.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error || 'Failed to delete booking'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error deleting booking:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete booking'
                    });
                });
            }
        });
    }
</script>

</x-app-layout>
