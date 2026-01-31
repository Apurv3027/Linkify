<nav class="navbar navbar-expand-lg navbar-light bg-white rounded mb-3 shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/">
            🔗 Linkify
        </a>

        <div class="d-flex align-items-center gap-2">
            @if(custom_user())
            <span class="small text-muted me-2">
                Hi, {{ custom_user()->name }}
            </span>

            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    Logout
                </button>
            </form>
            @else
            <a href="/login" class="btn btn-outline-primary btn-sm">
                Login
            </a>

            <a href="/register" class="btn btn-primary btn-sm">
                Register
            </a>
            @endif
        </div>
    </div>
</nav>
