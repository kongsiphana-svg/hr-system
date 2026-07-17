<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Portal - Admin Console</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .sidebar { height: 100vh; background-color: #1e2538; width: 240px; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; justify-content: space-between; z-index: 100; }
        .sidebar-top { padding: 20px; }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; color: #ffffff; font-weight: 600; margin-bottom: 30px; }
        .sidebar-brand i { background-color: #3b3db1; padding: 8px; border-radius: 6px; font-size: 1.1rem; }
        .nav-link-custom { display: flex; align-items: center; gap: 14px; padding: 12px 16px; color: #94a3b8; font-size: 0.9rem; font-weight: 500; text-decoration: none; border-radius: 8px; margin-bottom: 4px; transition: all 0.2s; }
        .nav-link-custom:hover, .nav-link-custom.active { color: #ffffff; background-color: rgba(255, 255, 255, 0.08); }
        .sidebar-bottom { padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.05); }
        .main-content { margin-left: 240px; }
        .top-navbar { background: #ffffff; padding: 15px 40px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .content-body { padding: 40px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-top">
            <div class="sidebar-brand">
                <i class="fa-solid fa-cubes"></i>
                <div>
                    <div class="lh-1">HR Portal</div>
                    <small style="font-size: 0.7rem; #ffffff ">Admin Console</small>
                </div>
            </div>
            <nav>
                <a href="{{ route('dashboard') }}" class="nav-link-custom {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-border-all"></i> Dashboard
                </a>
                <a href="{{ route('employees.index') }}" class="nav-link-custom {{ Request::routeIs('employees.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Employees
                </a>
                <a href="{{ route('schedule.index') }}" class="nav-link-custom {{ Request::routeIs('schedule.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i> Schedule
                </a>
                <a href="{{ route('payroll.index') }}" class="nav-link-custom {{ Request::routeIs('payroll.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-wallet"></i> Payroll
                </a>
                <a href="{{ route('leaves.index') }}" class="nav-link-custom {{ Request::routeIs('leaves.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check"></i> Leave Requests
                </a>
                <a href="{{ route('settings.index') }}" class="nav-link-custom {{ Request::routeIs('settings.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> Settings
                </a>
            </nav>
        </div>
        
        <div class="sidebar-bottom">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link-custom border-0 bg-transparent w-100 text-start p-0 m-0 text-white">
                    <i class="fa-solid fa-right-from-bracket text-danger"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="top-navbar">
            <h6 class="m-0 fw-bold text-dark">HRMS Admin</h6>
            <!-- Top Navbar User Info Section -->
            <a href="{{ route('profile.index') }}" class="d-flex align-items-center gap-3 text-decoration-none">
                <span class="small text-muted fw-semibold">{{ Auth::user()->name ?? 'Admin' }}</span>
                
                <!-- Dynamic Initials Avatar Badge -->
                <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold shadow-sm" 
                    style="width: 36px; height: 36px; background-color: #3b3db1; font-size: 0.85rem; letter-spacing: 0.5px;">
                    @php
                        // Extract initials dynamically from the user's name (e.g., "Log In" -> "LI")
                        $words = explode(' ', Auth::user()->name ?? 'Admin');
                        $initials = '';
                        foreach ($words as $word) {
                            $initials .= strtoupper(substr($word, 0, 1));
                        }
                        // Limit to max 2 letters just in case
                        echo substr($initials, 0, 2);
                    @endphp
                </div>
            </a>
        </div>

        <div class="content-body">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>