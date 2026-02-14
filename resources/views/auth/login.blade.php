<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Linkify</title>
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
            padding: 24px;
        }

        .card {
            border-radius: 18px;
            border: none;
            box-shadow: 0 25px 50px rgba(0, 0, 0, .25);
        }

        .brand {
            font-size: 2rem;
            font-weight: 800;
            color: #4f46e5;
        }
    </style>
</head>

<body>

    @if(session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0 shadow" role="alert"
            aria-live="assertive" aria-atomic="true">

            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                </button>
            </div>

        </div>
    </div>
    @endif

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <div class="brand">Linkify</div>
                        <p class="text-muted small mb-0">
                            Welcome back 👋
                        </p>
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger small">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <form method="POST" action="/login">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100 py-2">
                            Login
                        </button>
                    </form>

                    <div class="text-center mt-3 small">
                        Don't have an account?
                        <a href="/register" class="fw-semibold text-decoration-none">
                            Register
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastEl = document.getElementById('successToast');

        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl, {
                delay: 4000,
                autohide: true
            });

            toast.show();

            // Remove from DOM after hidden
            toastEl.addEventListener('hidden.bs.toast', function () {
                toastEl.remove();
            });
        }
    });
</script>


</html>
