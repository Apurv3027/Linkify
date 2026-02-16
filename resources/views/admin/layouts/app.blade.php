<!DOCTYPE html>
<html lang="en" data-bs-theme="light" id="htmlTheme">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Linkify Admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            min-height: 100vh;
            width: 250px;
            position: fixed;
            background: #111827;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .sidebar a:hover {
            background: #1f2937;
        }

        .content {
            margin-left: 260px;
            padding: 30px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h4 class="text-center text-white fw-bold mb-4">🔗 Linkify</h4>

        <a href="{{ route('admin.dashboard') }}"><i class="fa fa-gauge me-2"></i> Dashboard</a>
        <a href="{{ route('admin.users') }}"><i class="fa fa-users me-2"></i> Users</a>
        <a href="{{ route('admin.links') }}"><i class="fa fa-link me-2"></i> All Links</a>
        <a href="{{ route('admin.analytics') }}"><i class="fa fa-chart-bar me-2"></i> Analytics</a>
        <a href="{{ route('admin.reports') }}"><i class="fa fa-file me-2"></i> Reports</a>
        <a href="{{ route('admin.settings') }}"><i class="fa fa-cog me-2"></i> Settings</a>

        <button class="btn btn-danger w-100 mt-4" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="fa fa-sign-out-alt me-2"></i> Logout
        </button>
    </div>

    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>@yield('page-title')</h3>

            <button id="themeToggle" class="btn btn-outline-secondary btn-sm">
                <i id="themeIcon" class="fa fa-moon text-dark"></i>
            </button>
        </div>

        @yield('content')

    </div>

    {{-- Logout Confirmation Modal --}}
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Logout</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const html = document.getElementById('htmlTheme');
        const toggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');

        function setTheme(theme) {
            html.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);

            if (theme === 'dark') {
                themeIcon.classList.remove('fa-moon', 'text-dark');
                themeIcon.classList.add('fa-sun', 'text-white');
            } else {
                themeIcon.classList.remove('fa-sun', 'text-white');
                themeIcon.classList.add('fa-moon', 'text-dark');
            }
        }

        toggleBtn.addEventListener('click', function () {
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            setTheme(newTheme);
        });

        window.onload = function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);
        };
    </script>

    @stack('scripts')

</body>

</html>
