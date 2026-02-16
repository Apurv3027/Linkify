<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', config('app.name', 'Linkify'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }

        /* Header */
        .app-header {
            background: #ffffff;
            border-bottom: 1px solid #eaeaea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .navbar-glass {
            backdrop-filter: blur(6px);
        }

        /* Cards */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        }

        /* Buttons */
        .btn-primary {
            background: #4f46e5;
            border-color: #4f46e5;
        }

        .btn-primary:hover {
            background: #4338ca;
            border-color: #4338ca;
        }

        /* Footer */
        .app-footer {
            background: #ffffff;
            border-top: 1px solid #eaeaea;
        }

        .app-footer a:hover {
            text-decoration: underline;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- HEADER --}}
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-glass">
            <div class="container-fluid px-4 px-md-5">

                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    🔗 Linkify
                </a>

                <div class="d-flex align-items-center gap-2">

                    @if(custom_user())
                    <span class="small text-muted me-2">
                        Hi, {{ custom_user()->name }}
                    </span>

                    <form id="logoutForm" method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#logoutModal">
                            Logout
                        </button>
                    </form>

                    @else
                    <a href="{{ url('/login') }}" class="btn btn-outline-primary btn-sm">
                        Login
                    </a>
                    <a href="{{ url('/register') }}" class="btn btn-primary btn-sm">
                        Register
                    </a>
                    @endif

                </div>

            </div>
        </nav>
    </header>

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="app-footer mt-5">
        <div class="container-fluid px-4 px-md-5">
            <div class="py-2">
                <div class="row align-items-center text-center text-md-start">

                    <!-- Left -->
                    <div class="col-md-6 mb-1 mb-md-0">
                        <span class="small text-muted">
                            © {{ date('Y') }} <strong>Linkify</strong>
                        </span>
                    </div>

                    <!-- Right -->
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('privacy') }}" class="text-muted small text-decoration-none me-3">
                            Privacy
                        </a>
                        <a href="{{ route('terms') }}" class="text-muted small text-decoration-none me-3">
                            Terms
                        </a>
                        <a href="{{ route('support') }}" class="text-muted small text-decoration-none">
                            Support
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </footer>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">
                        Confirm Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-0 text-muted">
                        Are you sure you want to logout?
                    </p>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="submitLogout()">
                        Yes, Logout
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js (optional for analytics pages) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- TOAST MESSAGES --}}
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080">

        @if(session('success'))
        <div class="toast align-items-center text-bg-success border-0 shadow" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast align-items-center text-bg-danger border-0 shadow" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toastElList = [].slice.call(document.querySelectorAll('.toast'));
            toastElList.forEach(function (toastEl) {
                const toast = new bootstrap.Toast(toastEl, {
                    delay: 4000,   // ⏳ 4 seconds
                    autohide: true
                });
                toast.show();
            });
        });

        function submitLogout() {
            document.getElementById('logoutForm').submit();
        }
    </script>

    @stack('scripts')

</body>

</html>
