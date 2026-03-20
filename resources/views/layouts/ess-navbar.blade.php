      <div class="navbar">
        <div class="d-flex align-items-center gap-3">
          <!-- <button class="btn btn-light d-lg-none" id="mobileMenuBtn"><i class='bx bx-menu'></i></button>
          <form class="search-input d-none d-md-block">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0"><i class='bx bx-search'></i></span>
              <input class="form-control border-start-0" placeholder="Search trainings, payslips, requests..." />
            </div>
          </form> -->
        </div>

        <div class="d-flex align-items-center gap-3">
          <div class="d-none d-md-block text-muted small">{{ now()->format('l, M d, Y') }}</div>
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
              <div style="width:44px;height:44px;border-radius:8px;background:#eef2f7;display:flex;align-items:center;justify-content:center;margin-right:.5rem">
                <i class='bx bx-user'></i>
              </div>
              <div class="d-none d-sm-block text-end">
                <div style="font-weight:700">{{ Auth::user()->name ?? 'Employee' }}</div>
                <small class="text-muted">{{ Auth::user()->employee_id ?? '' }}</small>
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
              <li><a class="dropdown-item" href="{{ route('ess.profile') }}">Profile</a></li>
              <li><a class="dropdown-item" href="{{ route('ess.profile') }}">Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">@csrf
                  <button class="dropdown-item">Sign out</button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>