<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Linkify | Smart URL Shortener</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }

        .hero {
            padding: 120px 0 80px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
            text-align: center;
        }

        .hero h1 {
            font-weight: 800;
            font-size: 3.2rem;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .shortener-card {
            margin-top: -70px;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
        }

        .limit-badge {
            font-size: 0.85rem;
            background: #eef2ff;
            padding: 6px 12px;
            border-radius: 50px;
            color: #4f46e5;
        }

        .table thead {
            background: #4f46e5;
            color: white;
        }

        .copy-btn {
            border-radius: 8px;
        }

        .features-section {
            background: #ffffff;
        }

        .features-section h2 {
            color: #111827;
            font-size: 2.2rem;
        }

        .feature-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 60px rgba(79, 70, 229, 0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto;
            border-radius: 16px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon i {
            font-size: 28px;
            color: white;
        }

        footer {
            padding: 40px 0;
            text-align: center;
            background: #111827;
            color: white;
            margin-top: 60px;
        }
    </style>
</head>

<body>

    @php
    $guestCount = 0;
    $guestLinks = collect();

    if (!custom_user() && request()->cookie('guest_token')) {
    $guestLinks = \App\Models\Link::whereNull('user_id')
    ->where('guest_token', request()->cookie('guest_token'))
    ->latest()
    ->get();
    $guestCount = $guestLinks->count();
    }
    @endphp

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <h1>🔗 Linkify</h1>
            <h3>Smart URL Shortening for Modern Sharing</h3>
            <p class="mt-3 mb-4">
                Create short links, track clicks, and manage everything in one clean dashboard.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap mb-4">
                <a href="/login" class="btn btn-light btn-lg px-4">Login</a>
                <a href="/register" class="btn btn-outline-light btn-lg px-4">Create Account</a>
            </div>
        </div>
    </section>

    <!-- SHORTENER CARD -->
    <div class="container">
        <div class="card shortener-card p-4">

            <h5 class="fw-bold mb-3">Try it now</h5>

            @if(!custom_user())
            <div class="d-flex justify-content-between align-items-center mb-3">
                <small class="limit-badge">
                    Free Usage: {{ $guestCount }}/2 used
                </small>
                @if($guestCount >= 2)
                <small class="text-danger fw-semibold">Limit Reached</small>
                @endif
            </div>
            @endif

            <form id="shortenForm" method="POST" action="/shorten">
                @csrf
                <div class="row g-2">
                    <div class="col-md-9">
                        <input type="url" name="original_url" class="form-control form-control-lg"
                            placeholder="Paste your long URL here" required>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-link me-2"></i>Shorten
                        </button>
                    </div>
                </div>
            </form>

            <!-- Guest Links Table -->
            @if(!custom_user() && $guestLinks->count() > 0)
            <div class="mt-5">
                <h6 class="fw-bold mb-3">Your Short Links</h6>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Original URL</th>
                                <th>Short URL</th>
                                <th>Clicks</th>
                                <th>Copy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guestLinks as $link)
                            <tr>
                                <td class="text-truncate" style="max-width:250px;">
                                    {{ $link->original_url }}
                                </td>
                                <td>
                                    <a id="short-{{ $link->id }}" href="{{ url($link->short_code) }}" target="_blank">
                                        {{ url($link->short_code) }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $link->clicks ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary copy-btn"
                                        onclick="copyToClipboard('short-{{ $link->id }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- FEATURES SECTION -->
    <section class="features-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Why Choose Linkify?</h2>
                <p class="text-muted">Powerful features designed for modern link management.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card h-100 text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-link"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Fast URL Shortening</h5>
                        <p class="text-muted">
                            Instantly shorten any long URL with a single click.
                            Simple, fast and reliable.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card h-100 text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Track Analytics</h5>
                        <p class="text-muted">
                            Monitor clicks, user locations and performance
                            insights for every link.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card h-100 text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Easy Sharing</h5>
                        <p class="text-muted">
                            Share across social media, messaging apps,
                            and email effortlessly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} Linkify. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function copyToClipboard(id) {
            const element = document.getElementById(id);
            const text = element.value ?? element.innerText;

            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Short link copied to clipboard',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("shortenForm");
            if (!form) return;

            form.addEventListener("submit", function (e) {

                let guestUsed = {{ $guestCount }};
                let isLoggedIn = {{ custom_user() ? 'true' : 'false' }};

                if (!isLoggedIn && guestUsed >= 2) {

                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Free Limit Reached',
                        html: '<strong>2/2 used</strong><br>Please login to continue.',
                        showCancelButton: true,
                        confirmButtonText: 'Login Now',
                        cancelButtonText: 'Close'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/login';
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>
