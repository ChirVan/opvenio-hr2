{{-- Navbar: Matching reference design exactly --}}
<header class="h-16 bg-white flex items-center justify-between px-4 sm:px-6 relative shadow-[0_2px_8px_rgba(0,0,0,0.06)]">
    
    <!-- Border cover (removes the vertical line only in header height) -->
    <div class="hidden md:block absolute left-0 top-0 h-16 w-[2px] bg-white"></div>

    <div class="flex items-center gap-3">
        <!-- Mobile menu button / Sidebar toggle -->
        <button id="sidebarToggle"
            class="w-10 h-10 rounded-xl hover:bg-gray-100 active:bg-gray-200 transition flex items-center justify-center text-gray-600">
            <i class='bx bx-menu text-xl'></i>
        </button>
        
        <!-- Logo (visible in navbar) -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9">
            <div class="hidden sm:block leading-tight">
                <div class="font-bold text-gray-800 group-hover:text-emerald-600 transition-colors text-sm">
                    Microfinance HR
                </div>
                <div class="text-[10px] text-gray-500 font-semibold uppercase group-hover:text-emerald-600 transition-colors">
                    HUMAN RESOURCE II
                </div>
            </div>
        </a>
    </div>

    <div class="flex items-center gap-3 sm:gap-5">
        <!-- Clock pill -->
        <span id="real-time-clock"
            class="text-xs font-bold text-gray-700 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
            --:--:--
        </span>

        <!-- Notification Bell with Dropdown -->
        <div class="relative">
            <button id="notification-bell-btn"
                class="w-10 h-10 rounded-xl hover:bg-gray-100 active:bg-gray-200 transition flex items-center justify-center relative">
                <i class='bx bxs-bell text-xl text-amber-500'></i>
                <!-- Badge (hidden when no unread) -->
                <span id="notification-badge" class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-red-500 rounded-full border-2 border-white flex items-center justify-center hidden">
                    0
                </span>
            </button>

            <!-- Notification Dropdown Panel -->
            <div id="notification-dropdown"
                class="dropdown-panel hidden opacity-0 translate-y-2 scale-95 pointer-events-none
                       absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-xl shadow-xl border border-gray-100
                       transition-all duration-200 z-50 max-h-[70vh] overflow-hidden flex flex-col">
                
                <!-- Header -->
                <div class="px-4 py-3 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class='bx bxs-bell-ring text-amber-500'></i>
                        <span class="font-semibold text-gray-800">Notifications</span>
                        <span id="notification-count-label" class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">0 new</span>
                    </div>
                    <button id="mark-all-read-btn" class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                        Mark all read
                    </button>
                </div>

                <!-- Notification List -->
                <div id="notification-list" class="overflow-y-auto flex-1 max-h-80">
                    <!-- Loading state -->
                    <div id="notification-loading" class="p-6 text-center">
                        <i class='bx bx-loader-alt bx-spin text-2xl text-gray-400'></i>
                        <p class="text-sm text-gray-500 mt-2">Loading notifications...</p>
                    </div>
                    <!-- Empty state -->
                    <div id="notification-empty" class="p-6 text-center hidden">
                        <i class='bx bx-bell-off text-4xl text-gray-300'></i>
                        <p class="text-sm text-gray-500 mt-2">No notifications</p>
                    </div>
                    <!-- Notification items will be inserted here -->
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <button id="refresh-notifications-btn" class="text-xs text-gray-600 hover:text-gray-800 font-medium transition-colors flex items-center gap-1">
                        <i class='bx bx-refresh'></i> Refresh
                    </button>
                    <button id="clear-all-notifications-btn" class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors flex items-center gap-1">
                        <i class='bx bx-trash'></i> Clear All
                    </button>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

        <!-- User Profile Dropdown -->
        <div class="relative">
            <button id="user-menu-button"
                class="flex items-center gap-3 focus:outline-none group rounded-xl px-2 py-2
                       hover:bg-gray-100 active:bg-gray-200 transition">
                <!-- Avatar -->
                <div class="w-10 h-10 rounded-full bg-white shadow group-hover:shadow-md transition-shadow overflow-hidden flex items-center justify-center border border-gray-100">
                    <div class="w-full h-full flex items-center justify-center font-bold text-emerald-600 bg-emerald-50">
                        {{ strtoupper(substr(Auth::user() ? Auth::user()->name : 'G', 0, 1)) }}
                    </div>
                </div>
                <!-- Name & Role -->
                <div class="hidden md:flex flex-col items-start text-left">
                    <span class="text-sm font-bold text-gray-700 group-hover:text-emerald-600 transition-colors">
                        {{ Auth::user() ? Auth::user()->name : 'Guest' }}
                    </span>
                    <span class="text-[10px] text-gray-500 font-medium uppercase group-hover:text-emerald-600 transition-colors">
                        {{ Auth::user() && Auth::user()->role ? Auth::user()->role : 'User' }}
                    </span>
                </div>
                <!-- Chevron -->
                <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown Panel -->
            <div id="user-menu-dropdown"
                class="dropdown-panel hidden opacity-0 translate-y-2 scale-95 pointer-events-none
                       absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-lg border border-gray-100
                       transition-all duration-200 z-50">
                <a href="{{ route('profile.show') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition rounded-t-xl">
                    <i class='bx bx-user mr-2'></i> Profile
                </a>
                <a href="{{ route('profile.show') }}#two-factor" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-cog mr-2'></i> Settings
                </a>
                <a href="{{ route('audit.logs') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-history mr-2'></i> Audit Logs
                </a>
                <div class="h-px bg-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition rounded-b-xl">
                        <i class='bx bx-power-off mr-2'></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
    .dropdown-panel {
        transform-origin: top right;
    }
    .notification-item {
        transition: all 0.2s ease;
    }
    .notification-item:hover {
        background-color: #f9fafb;
    }
    .notification-item.unread {
        background-color: #eff6ff;
        border-left: 3px solid #3b82f6;
    }
    .notification-item.unread:hover {
        background-color: #dbeafe;
    }
    /* Toast animations */
    .toast-item {
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    /* Badge pulse animation */
    @keyframes badgePulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
        }
    }
    #notification-badge.animate-pulse {
        animation: badgePulse 1s ease-in-out infinite;
    }
    /* Bell shake on new notification */
    @keyframes bellShake {
        0%, 100% { transform: rotate(0); }
        10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
        20%, 40%, 60%, 80% { transform: rotate(10deg); }
    }
    .bell-shake {
        animation: bellShake 0.5s ease-in-out;
    }
    /* Line clamp for notification text */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Realtime Clock
    const clockEl = document.getElementById('real-time-clock');
    const updateClock = () => {
        if (!clockEl) return;
        const now = new Date();
        clockEl.textContent = now.toLocaleTimeString('en-US', {
            hour12: true,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        });
    };
    if (clockEl) {
        updateClock();
        setInterval(updateClock, 1000);
    }

    // ============================================
    // Notification System - Real-time AJAX
    // ============================================
    const notificationBellBtn = document.getElementById('notification-bell-btn');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const notificationBadge = document.getElementById('notification-badge');
    const notificationCountLabel = document.getElementById('notification-count-label');
    const notificationList = document.getElementById('notification-list');
    const notificationLoading = document.getElementById('notification-loading');
    const notificationEmpty = document.getElementById('notification-empty');
    const markAllReadBtn = document.getElementById('mark-all-read-btn');
    const refreshNotificationsBtn = document.getElementById('refresh-notifications-btn');
    const clearAllNotificationsBtn = document.getElementById('clear-all-notifications-btn');

    let notificationsLoaded = false;
    let lastNotificationCount = 0;
    let lastCheckTimestamp = Date.now();
    let isPolling = true;

    // Open/Close Notification Dropdown
    const openNotificationDropdown = () => {
        if (!notificationDropdown) return;
        notificationDropdown.classList.remove('hidden');
        requestAnimationFrame(() => {
            notificationDropdown.classList.remove('opacity-0', 'translate-y-2', 'scale-95', 'pointer-events-none');
            notificationDropdown.classList.add('opacity-100', 'translate-y-0', 'scale-100', 'pointer-events-auto');
        });
        // Always refresh notifications when dropdown opens
        loadNotifications();
    };

    const closeNotificationDropdown = () => {
        if (!notificationDropdown) return;
        notificationDropdown.classList.add('opacity-0', 'translate-y-2', 'scale-95', 'pointer-events-none');
        notificationDropdown.classList.remove('opacity-100', 'translate-y-0', 'scale-100', 'pointer-events-auto');
        setTimeout(() => notificationDropdown.classList.add('hidden'), 200);
    };

    if (notificationBellBtn && notificationDropdown) {
        notificationBellBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            // Close user dropdown if open
            if (userDropdown && !userDropdown.classList.contains('hidden')) {
                closeDropdown();
            }
            const isHidden = notificationDropdown.classList.contains('hidden');
            if (isHidden) openNotificationDropdown();
            else closeNotificationDropdown();
        });
    }

    // Show toast notification
    function showToast(title, message, type = 'info') {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        
        const iconMap = {
            'success': 'bx-check-circle text-emerald-500',
            'error': 'bx-error-circle text-red-500',
            'warning': 'bx-error text-amber-500',
            'info': 'bx-info-circle text-blue-500',
            'hr4': 'bx-user-plus text-blue-500',
            'training': 'bx-check-circle text-emerald-500'
        };
        
        const bgMap = {
            'success': 'border-l-emerald-500',
            'error': 'border-l-red-500',
            'warning': 'border-l-amber-500',
            'info': 'border-l-blue-500',
            'hr4': 'border-l-blue-500',
            'training': 'border-l-emerald-500'
        };

        const toast = document.createElement('div');
        toast.className = `toast-item bg-white rounded-lg shadow-lg border border-gray-200 border-l-4 ${bgMap[type] || bgMap.info} p-4 mb-3 transform translate-x-full opacity-0 transition-all duration-300 max-w-sm`;
        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <i class='bx ${iconMap[type] || iconMap.info} text-xl flex-shrink-0 mt-0.5'></i>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">${title}</p>
                    <p class="text-xs text-gray-600 mt-0.5 line-clamp-2">${message}</p>
                </div>
                <button class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0" onclick="this.closest('.toast-item').remove()">
                    <i class='bx bx-x text-lg'></i>
                </button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        });
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
        
        // Shake the bell icon
        shakeBellIcon();
    }

    function shakeBellIcon() {
        const bellIcon = document.querySelector('#notification-bell-btn i');
        if (bellIcon) {
            bellIcon.classList.add('bell-shake');
            setTimeout(() => bellIcon.classList.remove('bell-shake'), 500);
        }
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-20 right-4 z-[100] flex flex-col items-end';
        document.body.appendChild(container);
        return container;
    }

    // Load notifications from API with AJAX
    async function loadNotifications() {
        if (notificationLoading) {
            notificationLoading.classList.remove('hidden');
            notificationLoading.innerHTML = `
                <div class="p-4 text-center">
                    <i class='bx bx-loader-alt bx-spin text-2xl text-blue-500'></i>
                    <p class="text-sm text-gray-500 mt-2">Loading notifications...</p>
                </div>
            `;
        }
        if (notificationEmpty) notificationEmpty.classList.add('hidden');

        try {
            const response = await fetch('{{ route("notifications.index") }}?_=' + Date.now(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache'
                }
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();

            if (data.success) {
                notificationsLoaded = true;
                renderNotifications(data.notifications);
                
                // Check for new notifications and show toast
                if (data.unread_count > lastNotificationCount && lastNotificationCount > 0) {
                    const newCount = data.unread_count - lastNotificationCount;
                    showToast('New Notification', `You have ${newCount} new notification${newCount > 1 ? 's' : ''}`, 'info');
                    // Play notification sound (optional)
                    playNotificationSound();
                }
                
                lastNotificationCount = data.unread_count;
                updateBadge(data.unread_count);
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
            if (notificationLoading) {
                notificationLoading.innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        <i class='bx bx-error-circle text-2xl'></i>
                        <p class="text-sm mt-1">Failed to load</p>
                        <button onclick="loadNotifications()" class="text-xs text-blue-600 hover:underline mt-2">Retry</button>
                    </div>
                `;
            }
        }
    }

    // Optional: Play notification sound
    function playNotificationSound() {
        try {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleT82mubj1KljNRp8rNjMq38mAUqPxdnMm30qIXWl2NHHjVoROHu12cawcjQOQ4fL2K5kKA1Vn9TRoFkiHXe56NmocSAKUJfT1KNkLRhqtOPbr3AmDVGh3dalYicWdLXo3q9sJAxZpd/bpGAkFnq57N+wbCMOXqri3aRgJBZ6uezeqWokEWCx5d+qZSQUd7bs4K5oIxFitenepWMiE3m47uCvaiIQYrTq4KdkIhF3uO7hrmghEGOz6uGoZSEPdbfv4q5nIRFltOriqGYgD3W47+KuZyERZbPq4qhmIA91uO/irmchEWWz6uKoZh8Odbfv4q1mIRFms+riqGYfDnW37+KuZiARZrPq4qhmHw51t+/irmYgEWaz6uKoZh8Odbfv4q5mIBFms+riqGYfDnW37+KuZiARZrPq4qhmHw51t+/irmYgEWaz6uKoZh8Odbfv4q1mIRFms+riqGYfDnW37+KuZiARZrPq4qhmHw51t+/irmYgEWaz6uKoZh8Odbfv4q1mIQ==');
            audio.volume = 0.3;
            audio.play().catch(() => {}); // Ignore errors if sound can't play
        } catch (e) {}
    }

    // Render notifications
    function renderNotifications(notifications) {
        if (notificationLoading) notificationLoading.classList.add('hidden');
        
        // Remove existing notification items
        const existingItems = notificationList.querySelectorAll('.notification-item');
        existingItems.forEach(item => item.remove());

        if (!notifications || notifications.length === 0) {
            if (notificationEmpty) notificationEmpty.classList.remove('hidden');
            return;
        }

        notifications.forEach(notification => {
            const item = document.createElement('div');
            item.className = `notification-item p-3 border-b border-gray-100 cursor-pointer ${notification.is_read ? '' : 'unread'}`;
            item.dataset.id = notification.id;
            item.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class='bx ${notification.icon} ${notification.icon_color} text-lg'></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 ${notification.is_read ? '' : 'font-semibold'}">${notification.title}</p>
                        <p class="text-xs text-gray-600 mt-0.5 line-clamp-2">${notification.message}</p>
                        <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                            <i class='bx bx-time-five'></i> ${notification.time_ago}
                        </p>
                    </div>
                    ${!notification.is_read ? '<span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0 animate-pulse"></span>' : ''}
                </div>
            `;

            // Click to mark as read and navigate
            item.addEventListener('click', () => markAsReadAndNavigate(notification));
            
            notificationList.appendChild(item);
        });
    }

    // Update badge count with animation
    function updateBadge(count) {
        if (notificationBadge) {
            if (count > 0) {
                notificationBadge.textContent = count > 99 ? '99+' : count;
                notificationBadge.classList.remove('hidden');
                // Add pulse animation for new notifications
                notificationBadge.classList.add('animate-pulse');
                setTimeout(() => notificationBadge.classList.remove('animate-pulse'), 2000);
            } else {
                notificationBadge.classList.add('hidden');
            }
        }
        if (notificationCountLabel) {
            notificationCountLabel.textContent = `${count} new`;
        }
    }

    // Mark as read and navigate - AJAX
    async function markAsReadAndNavigate(notification) {
        if (!notification.is_read) {
            try {
                await fetch(`{{ url('notifications') }}/${notification.id}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                // Update UI immediately
                const item = document.querySelector(`.notification-item[data-id="${notification.id}"]`);
                if (item) {
                    item.classList.remove('unread');
                    const dot = item.querySelector('.animate-pulse');
                    if (dot) dot.remove();
                }
                lastNotificationCount = Math.max(0, lastNotificationCount - 1);
                updateBadge(lastNotificationCount);
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }
        
        if (notification.link) {
            window.location.href = notification.link;
        } else {
            closeNotificationDropdown();
        }
    }

    // Mark all as read - AJAX
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', async (e) => {
            e.stopPropagation();
            markAllReadBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Processing...';
            try {
                const response = await fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                if (response.ok) {
                    lastNotificationCount = 0;
                    updateBadge(0);
                    // Update UI immediately
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        const dot = item.querySelector('.animate-pulse');
                        if (dot) dot.remove();
                    });
                    showToast('Success', 'All notifications marked as read', 'success');
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
                showToast('Error', 'Failed to mark notifications as read', 'error');
            }
            markAllReadBtn.innerHTML = 'Mark all read';
        });
    }

    // Refresh notifications
    if (refreshNotificationsBtn) {
        refreshNotificationsBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            refreshNotificationsBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Refreshing...';
            loadNotifications().then(() => {
                refreshNotificationsBtn.innerHTML = '<i class="bx bx-refresh"></i> Refresh';
            });
        });
    }

    // Clear all notifications - AJAX
    if (clearAllNotificationsBtn) {
        clearAllNotificationsBtn.addEventListener('click', async (e) => {
            e.stopPropagation();
            if (!confirm('Clear all notifications?')) return;
            clearAllNotificationsBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Clearing...';
            try {
                const response = await fetch('{{ route("notifications.clear-all") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                if (response.ok) {
                    lastNotificationCount = 0;
                    updateBadge(0);
                    loadNotifications();
                    showToast('Success', 'All notifications cleared', 'success');
                }
            } catch (error) {
                console.error('Error clearing notifications:', error);
                showToast('Error', 'Failed to clear notifications', 'error');
            }
            clearAllNotificationsBtn.innerHTML = '<i class="bx bx-trash"></i> Clear All';
        });
    }

    // Real-time polling for new data
    async function checkForUpdates() {
        if (!isPolling) return;
        
        try {
            // Check unread count
            const countResponse = await fetch('{{ route("notifications.unread-count") }}?_=' + Date.now(), {
                headers: { 
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });
            const countData = await countResponse.json();
            
            if (countData.success) {
                // If new notifications arrived
                if (countData.unread_count > lastNotificationCount) {
                    const newCount = countData.unread_count - lastNotificationCount;
                    showToast('New Notification', `You have ${newCount} new notification${newCount > 1 ? 's' : ''}!`, 'info');
                    playNotificationSound();
                    
                    // Refresh notifications if dropdown is open
                    if (notificationDropdown && !notificationDropdown.classList.contains('hidden')) {
                        loadNotifications();
                    }
                }
                lastNotificationCount = countData.unread_count;
                updateBadge(countData.unread_count);
            }

            // Check for HR4 updates (potential new employees)
            const hr4Response = await fetch('{{ route("notifications.check-hr4") }}?_=' + Date.now(), {
                headers: { 
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });
            const hr4Data = await hr4Response.json();
            
            if (hr4Data.success && hr4Data.has_updates && hr4Data.potential_new > 0) {
                // Store in session to avoid repeated toasts
                const lastHr4Check = sessionStorage.getItem('lastHr4NewCount') || '0';
                if (parseInt(lastHr4Check) !== hr4Data.potential_new) {
                    showToast('HR4 Update Available', `${hr4Data.potential_new} potential new employee(s) detected in HR4. Click "Sync HR4" to import.`, 'hr4');
                    sessionStorage.setItem('lastHr4NewCount', hr4Data.potential_new.toString());
                }
            }

            // Check for pending training room bookings
            const trainingResponse = await fetch('{{ route("notifications.check-training-rooms") }}?_=' + Date.now(), {
                headers: { 
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });
            const trainingData = await trainingResponse.json();
            
            if (trainingData.success && trainingData.recent_approved > 0) {
                const lastApprovedCheck = sessionStorage.getItem('lastApprovedCount') || '0';
                if (parseInt(lastApprovedCheck) !== trainingData.recent_approved) {
                    showToast('Training Room Update', `${trainingData.recent_approved} training room booking(s) recently approved!`, 'training');
                    sessionStorage.setItem('lastApprovedCount', trainingData.recent_approved.toString());
                }
            }

        } catch (error) {
            // Silent fail for polling
            console.log('Polling check failed:', error);
        }
    }

    // Start real-time polling (every 15 seconds for more real-time feel)
    setInterval(checkForUpdates, 15000);

    // Initial load
    (async () => {
        try {
            const response = await fetch('{{ route("notifications.unread-count") }}', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            if (data.success) {
                lastNotificationCount = data.unread_count;
                updateBadge(data.unread_count);
            }
        } catch (error) {
            // Silent fail
        }
        
        // Also check for updates on initial load
        setTimeout(checkForUpdates, 2000);
    })();

    // ============================================
    // User Dropdown
    // ============================================
    const userBtn = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-menu-dropdown');

    const openDropdown = () => {
        if (!userDropdown) return;
        userDropdown.classList.remove('hidden');
        requestAnimationFrame(() => {
            userDropdown.classList.remove('opacity-0', 'translate-y-2', 'scale-95', 'pointer-events-none');
            userDropdown.classList.add('opacity-100', 'translate-y-0', 'scale-100', 'pointer-events-auto');
        });
    };

    const closeDropdown = () => {
        if (!userDropdown) return;
        userDropdown.classList.add('opacity-0', 'translate-y-2', 'scale-95', 'pointer-events-none');
        userDropdown.classList.remove('opacity-100', 'translate-y-0', 'scale-100', 'pointer-events-auto');
        setTimeout(() => userDropdown.classList.add('hidden'), 200);
    };

    if (userBtn && userDropdown) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            // Close notification dropdown if open
            if (notificationDropdown && !notificationDropdown.classList.contains('hidden')) {
                closeNotificationDropdown();
            }
            const isHidden = userDropdown.classList.contains('hidden');
            if (isHidden) openDropdown();
            else closeDropdown();
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        if (userDropdown && !userDropdown.classList.contains('hidden')) closeDropdown();
        if (notificationDropdown && !notificationDropdown.classList.contains('hidden')) closeNotificationDropdown();
    });

    // Prevent dropdown close when clicking inside
    if (notificationDropdown) {
        notificationDropdown.addEventListener('click', (e) => e.stopPropagation());
    }
    if (userDropdown) {
        userDropdown.addEventListener('click', (e) => e.stopPropagation());
    }

    // Mobile Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const mobileBtn = document.getElementById('mobile-menu-btn');

    const openSidebar = () => {
        if (!sidebar || !overlay) return;
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        requestAnimationFrame(() => overlay.classList.remove('opacity-0'));
    };

    const closeSidebar = () => {
        if (!sidebar || !overlay) return;
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    };

    if (mobileBtn && sidebar) {
        mobileBtn.addEventListener('click', () => {
            const closed = sidebar.classList.contains('-translate-x-full');
            if (closed) openSidebar();
            else closeSidebar();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
});
</script>
