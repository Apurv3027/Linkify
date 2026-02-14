<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Linkify | Smart URL Shortener</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
        }

        .hero {
            padding: 100px 0 60px;
            text-align: center;
        }

        .brand {
            font-size: 3rem;
            font-weight: 800;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: auto;
        }

        .btn-primary {
            background: #4f46e5;
            border: none;
        }

        .btn-outline-light {
            border-color: #fff;
            color: #fff;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }

        .features {
            padding: 80px 0;
            text-align: center;
        }

        .feature-card {
            background: #fff;
            color: #333;
            border-radius: 16px;
            padding: 40px 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-card i {
            font-size: 3rem;
            color: #4f46e5;
            margin-bottom: 20px;
        }

        footer {
            padding: 40px 0;
            text-align: center;
            background: rgba(0, 0, 0, 0.2);
        }

        input[readonly] {
            background: #f1f1f1;
        }
    </style>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- TOAST MESSAGE -->
    @if(session('success'))
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080">
        <div id="logoutToast" class="toast align-items-center text-bg-success border-0 shadow" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="container">
            <div class="brand mb-3">🔗 Linkify</div>
            <p class="lead mb-4">
                Shorten links, share files, and track clicks — all in one smart dashboard.
            </p>

            <div class="d-flex justify-content-center gap-3 mb-5 flex-wrap">
                <a href="/login" class="btn btn-light btn-lg px-4">Login</a>
                <a href="/register" class="btn btn-outline-light btn-lg px-4">Create Account</a>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card p-4 text-dark">
                        <h5 class="fw-bold mb-3">Try it now — Shorten a link</h5>
                        <form method="POST" action="/shorten">
                            @csrf
                            <input type="url" name="original_url" class="form-control form-control-lg mb-3"
                                placeholder="Paste your long URL here" required>
                            <button class="btn btn-primary btn-lg w-100">Shorten URL</button>
                        </form>

                        @if(session('shortUrl'))
                        <div class="mt-4">
                            <label class="fw-semibold mb-1">Your Short Link</label>
                            <input class="form-control" value="{{ session('shortUrl') }}" readonly>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features text-light">
        <div class="container">
            <h2 class="fw-bold mb-5">Why Choose Linkify?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-link"></i>
                        <h5 class="fw-bold mb-2">Fast URL Shortening</h5>
                        <p>Instantly shorten any long URL with a single click. Simple and reliable.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-chart-line"></i>
                        <h5 class="fw-bold mb-2">Track Analytics</h5>
                        <p>Monitor clicks, locations, and referrers for every short link you create.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-share-alt"></i>
                        <h5 class="fw-bold mb-2">Easy Sharing</h5>
                        <p>Share your links effortlessly across social media, email, and messaging platforms.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; {{ date('Y') }} Linkify. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toastEl = document.getElementById('logoutToast');
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl, { delay: 4000, autohide: true });
                toast.show();
                toastEl.addEventListener('hidden.bs.toast', function () { toastEl.remove(); });
            }
        });
    </script>

</body>

</html>
