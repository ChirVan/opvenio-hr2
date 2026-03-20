<style>
  /* Layout */
    .app-shell{display:flex;min-height:100vh}
    .sidebar{width:260px;background:white;border-right:1px solid #eef2f7;padding:1.25rem;}
    .sidebar .brand{display:flex;align-items:center;gap:.75rem;padding-bottom:1rem;margin-bottom:1rem;border-bottom:1px solid #f1f5f9}
    .sidebar .nav-link{color:var(--muted);font-weight:500;border-radius:.5rem;padding:.6rem .75rem}
    .sidebar .nav-link.active{background:linear-gradient(90deg,var(--primary)10%,#6ee7b7);color:white;box-shadow:var(--card-shadow)}
    .sidebar .nav-link i{font-size:1.1rem;margin-right:.6rem}
</style>
<aside class="sidebar d-none d-lg-block">
      <div class="brand">
        <!-- <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--primary),#059669);display:flex;align-items:center;justify-content:center;color:white;font-weight:700">EP</div> -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 36px; height: 36px; object-fit: contain;">
        <div>
          <div style="font-weight:700">{{ config('app.name', 'Employee Portal') }}</div>
          <small style="color:var(--muted)">{{ Auth::user()->name ?? 'Employee' }}</small>
        </div>
      </div>

      <nav class="nav flex-column">
        <a href="{{ route('ess.dashboard') }}" class="nav-link active"><i class='bx bx-home'></i> Dashboard</a>
        <a href="{{ route('ess.lms') }}" class="nav-link"><i class='bx bx-book-open'></i> Learning</a>
        <a href="{{ route('ess.leave') }}" class="nav-link"><i class='bx bx-calendar-event'></i> Time Off</a>
        <a href="{{ route('ess.payslip') }}" class="nav-link"><i class='bx bx-wallet'></i> Payslip</a>
        <a href="{{ route('ess.promotion-offers') }}" class="nav-link"><i class='bx bx-rocket'></i> Promotions</a>
        <hr style="margin:1rem 0;border-color:#f3f6f9">
        <!-- <a href="#" class="nav-link"><i class='bx bx-help-circle'></i> Help & Support</a> -->
        <!-- <a href="#" class="nav-link"><i class='bx bx-cog'></i> Settings</a> -->
      </nav>

      <div style="position:absolute;bottom:1.25rem;left:1.25rem;right:1.25rem">
        <!-- <small class="text-muted">Need help? <a href="#">Contact HR</a></small> -->
      </div>
    </aside>