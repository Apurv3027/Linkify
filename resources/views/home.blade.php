<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Linkify | Smart URL Shortener</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
        }

        .hero {
            padding: 80px 0;
        }

        .card {
            border-radius: 18px;
            border: none;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .25);
        }

        .brand {
            font-size: 3rem;
            font-weight: 800;
        }

    </style>
</head>
<body>

    <div class="container hero text-center">
        <div class="brand mb-3">🔗 Linkify</div>
        <p class="lead mb-4">
            Shorten links. Share files. Track clicks.
            All in one smart dashboard.
        </p>

        <div class="d-flex justify-content-center gap-3 mb-5">
            <a href="/login" class="btn btn-light btn-lg px-4">Login</a>
            <a href="/register" class="btn btn-outline-light btn-lg px-4">Create Account</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4 text-dark">
                    <h5 class="fw-bold mb-3">Try it now — Shorten a link</h5>

                    <form method="POST" action="/shorten">
                        @csrf
                        <input type="url" name="original_url" class="form-control form-control-lg mb-3" placeholder="Paste long URL here" required>
                        <button class="btn btn-primary btn-lg w-100">
                            Shorten URL
                        </button>
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

</body>
</html>
