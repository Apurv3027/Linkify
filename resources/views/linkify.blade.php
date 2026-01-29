<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Linkify | URL Shortener</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
        }

        .brand {
            font-size: 2rem;
            font-weight: 700;
            color: #4f46e5;
        }

        .copy-btn {
            min-width: 90px;
        }

        .qr-box img {
            max-width: 180px;
        }

    </style>
</head>

<body>
    <div class="container px-3">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <div class="card p-4">
                    <div class="text-center mb-3">
                        <div class="brand">🔗 Linkify</div>
                        <p class="text-muted mb-0">
                            A lightweight URL shortener built with Laravel
                        </p>
                    </div>

                    <!-- Shorten Form -->
                    <form method="POST" action="/shorten">
                        @csrf

                        <input type="url" name="original_url" class="form-control form-control-lg mb-3" placeholder="Paste your long URL here" required>

                        {{-- <label class="small text-muted">Expiry (optional)</label>
                        <input type="datetime-local" name="expires_at" class="form-control mb-3"> --}}

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Shorten URL
                        </button>
                    </form>

                    <!-- Short URL Result -->
                    @if(session('shortUrl'))
                    <div class="mt-4">
                        <label class="fw-semibold">Short URL</label>
                        <div class="input-group mb-3">
                            <input id="shortUrl" class="form-control" value="{{ session('shortUrl') }}" readonly>
                            <button class="btn btn-outline-primary copy-btn" onclick="copyUrl()">
                                Copy
                            </button>
                        </div>

                        <small id="copyMsg" class="text-success d-none">
                            ✔ Copied to clipboard
                        </small>
                    </div>
                    @endif

                    <!-- History Dashboard -->
                    @if(isset($links) && $links->count())
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Recent Links</h5>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Short</th>
                                    <th>Clicks</th>
                                    {{-- <th>Expiry</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($links as $link)
                                <tr>
                                    <td>
                                        <a href="{{ url($link->short_code) }}" target="_blank">
                                            {{ $link->short_code }}
                                        </a>
                                    </td>
                                    <td>{{ $link->clicks }}</td>
                                    {{-- <td>
                                        {{ $link->expires_at
                                            ? $link->expires_at->format('d M Y, h:i a')
                                            : 'Never' }}
                                    </td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <p class="text-center text-white small mt-3">
                    © {{ date('Y') }} Linkify • Portfolio Project
                </p>

            </div>
        </div>
    </div>

    <script>
        function copyUrl() {
            const input = document.getElementById('shortUrl');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);

            const msg = document.getElementById('copyMsg');
            msg.classList.remove('d-none');
            setTimeout(() => msg.classList.add('d-none'), 2000);
        }

    </script>
</body>
</html>
