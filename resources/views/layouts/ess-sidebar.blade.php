<!-- ESS Sidebar -->
<div id="sidebar-wrapper" class="sidebar-wrapper bg-white shadow-lg">
    <div class="sidebar">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="bg-blue-600 text-white rounded-lg p-2 mr-3">
                    <i class='bx bx-user text-xl'></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Employee Portal</h2>
                    <p class="text-sm text-gray-500">{{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>

        <nav class="mt-6">
            <ul class="space-y-2">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('ess.dashboard') }}" 
                       class="sidebar-link {{ request()->routeIs('ess.dashboard') ? 'active' : '' }}">
                        <i class='bx bx-home text-xl'></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- My Profile -->
                <li>
                    <a href="#" class="sidebar-link">
                        <i class='bx bx-user text-xl'></i>
                        <span>My Profile</span>
                    </a>
                </li>

                <!-- Learning & Development -->
                <li>
                    <div class="sidebar-section-header">
                        <i class='bx bx-book-open text-xl'></i>
                        <span>Learning & Development</span>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <ul class="sidebar-submenu">
                        <li><a href="#" class="sidebar-sublink">My Courses</a></li>
                        <li><a href="#" class="sidebar-sublink">Training Calendar</a></li>
                        <li><a href="#" class="sidebar-sublink">Assessments</a></li>
                        <li><a href="#" class="sidebar-sublink">Certificates</a></li>
                    </ul>
                </li>

                <!-- Performance Management -->
                <li>
                    <div class="sidebar-section-header">
                        <i class='bx bx-target-lock text-xl'></i>
                        <span>Performance</span>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <ul class="sidebar-submenu">
                        <li><a href="#" class="sidebar-sublink">My Goals</a></li>
                        <li><a href="#" class="sidebar-sublink">Performance Reviews</a></li>
                        <li><a href="#" class="sidebar-sublink">Feedback</a></li>
                        <li><a href="#" class="sidebar-sublink">Development Plan</a></li>
                    </ul>
                </li>

                <!-- Time & Attendance -->
                <li>
                    <div class="sidebar-section-header">
                        <i class='bx bx-time text-xl'></i>
                        <span>Time & Attendance</span>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <ul class="sidebar-submenu">
                        <li><a href="#" class="sidebar-sublink">Clock In/Out</a></li>
                        <li><a href="#" class="sidebar-sublink">Timesheet</a></li>
                        <li><a href="#" class="sidebar-sublink">Overtime</a></li>
                    </ul>
                </li>

                <!-- Leave Management -->
                <li>
                    <div class="sidebar-section-header">
                        <i class='bx bx-calendar text-xl'></i>
                        <span>Leave Management</span>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <ul class="sidebar-submenu">
                        <li><a href="#" class="sidebar-sublink">Request Leave</a></li>
                        <li><a href="#" class="sidebar-sublink">Leave History</a></li>
                        <li><a href="#" class="sidebar-sublink">Leave Balance</a></li>
                        <li><a href="#" class="sidebar-sublink">Company Calendar</a></li>
                    </ul>
                </li>

                <!-- Payroll & Benefits -->
                <li>
                    <div class="sidebar-section-header">
                        <i class='bx bx-money text-xl'></i>
                        <span>Payroll & Benefits</span>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <ul class="sidebar-submenu">
                        <li><a href="#" class="sidebar-sublink">Pay Stubs</a></li>
                        <li><a href="#" class="sidebar-sublink">Tax Documents</a></li>
                        <li><a href="#" class="sidebar-sublink">Benefits Overview</a></li>
                        <li><a href="#" class="sidebar-sublink">Retirement Plan</a></li>
                    </ul>
                </li>

                <!-- Company Information -->
                <li>
                    <div class="sidebar-section-header">
                        <i class='bx bx-building text-xl'></i>
                        <span>Company</span>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <ul class="sidebar-submenu">
                        <li><a href="#" class="sidebar-sublink">Organization Chart</a></li>
                        <li><a href="#" class="sidebar-sublink">Employee Directory</a></li>
                        <li><a href="#" class="sidebar-sublink">Policies</a></li>
                        <li><a href="#" class="sidebar-sublink">News & Updates</a></li>
                    </ul>
                </li>

                <!-- Support -->
                <li>
                    <a href="#" class="sidebar-link">
                        <i class='bx bx-help-circle text-xl'></i>
                        <span>Help & Support</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Bottom Section -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
            <div class="flex items-center justify-between text-sm text-gray-500">
                <span>ESS v1.0</span>
                <a href="#" class="hover:text-gray-700">
                    <i class='bx bx-cog'></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* ESS Sidebar Specific Styles */
.sidebar-link {
    @apply flex items-center px-4 py-3 mx-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200;
}

.sidebar-link.active {
    @apply bg-blue-100 text-blue-700 font-medium;
}

.sidebar-section-header {
    @apply flex items-center justify-between px-4 py-3 mx-3 text-gray-700 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200;
}

.sidebar-submenu {
    @apply hidden mt-1 space-y-1;
}

.sidebar-submenu.show {
    @apply block;
}

.sidebar-sublink {
    @apply block px-8 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors duration-200;
}

.toggle-icon {
    @apply transition-transform duration-200;
}

.toggle-icon.rotate {
    @apply transform rotate-180;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle sidebar submenu toggles
    const sectionHeaders = document.querySelectorAll('.sidebar-section-header');
    
    sectionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const submenu = this.nextElementSibling;
            const toggleIcon = this.querySelector('.toggle-icon');
            
            if (submenu && submenu.classList.contains('sidebar-submenu')) {
                submenu.classList.toggle('show');
                toggleIcon.classList.toggle('rotate');
            }
        });
    });
});
</script>