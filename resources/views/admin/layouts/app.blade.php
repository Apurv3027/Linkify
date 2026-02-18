<!DOCTYPE html>
<html lang="en" data-bs-theme="light" id="htmlTheme">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Linkify Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #0f172a;
            padding: 24px 18px;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 6px;
            font-weight: 500;
            transition: 0.2s ease;
        }

        .sidebar a:hover {
            background: #1e293b;
            color: #fff;
        }

        .sidebar a.active {
            background: #2563eb;
            color: #fff;
        }

        .sidebar .logout-btn {
            margin-top: 30px;
        }

        /* Content */
        .content {
            margin-left: 260px;
            padding: 35px;
            transition: 0.3s ease;
        }

        /* Mobile */
        @media(max-width: 992px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    {{-- Sidebar --}}
    <div class="sidebar" id="sidebar">

        <div class="sidebar-brand text-center">
            🔗 Linkify
        </div>

        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa fa-gauge"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fa fa-users"></i>
            <span>Users</span>
        </a>

        <a href="{{ route('admin.links') }}" class="{{ request()->routeIs('admin.links*') ? 'active' : '' }}">
            <i class="fa fa-link"></i>
            <span>Links</span>
        </a>

        <a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <i class="fa fa-chart-line"></i>
            <span>Analytics</span>
        </a>

        <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <i class="fa fa-gear"></i>
            <span>Settings</span>
        </a>

        <div class="logout-btn">
            <button class="btn btn-danger w-100 rounded-3 mt-3" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fa fa-sign-out-alt me-2"></i> Logout
            </button>
        </div>

    </div>


    {{-- Content --}}
    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" onclick="toggleSidebar()">
                    <i class="fa fa-bars"></i>
                </button>

                <h4 class="mb-0">@yield('page-title')</h4>
            </div>

            <button id="themeToggle" class="btn btn-outline-secondary btn-sm">
                <i id="themeIcon" class="fa fa-moon text-dark"></i>
            </button>
        </div>

        @yield('content')

    </div>


    {{-- Logout Modal --}}
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Logout</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="btn btn-danger">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const html = document.getElementById('htmlTheme');
    const toggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const sidebar = document.getElementById('sidebar');

    function setTheme(theme) {
        html.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);

        if (theme === 'dark') {
            themeIcon.classList.replace('fa-moon', 'fa-sun');
            themeIcon.classList.remove('text-dark');
            themeIcon.classList.add('text-white');
        } else {
            themeIcon.classList.replace('fa-sun', 'fa-moon');
            themeIcon.classList.remove('text-white');
            themeIcon.classList.add('text-dark');
        }
    }

    toggleBtn.addEventListener('click', function () {
        const current = html.getAttribute('data-bs-theme');
        setTheme(current === 'light' ? 'dark' : 'light');
    });

    window.onload = function () {
        setTheme(localStorage.getItem('theme') || 'light');
    };

    function toggleSidebar() {
        sidebar.classList.toggle('show');
    }
    </script>

    @stack('scripts')

</body>

</html>
