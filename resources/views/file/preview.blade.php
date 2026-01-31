<!DOCTYPE html>
<html lang="en">
<head>
    <title>File Preview | Linkify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6fb;
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .08);
        }

    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">

                <div class="card p-4 text-center">

                    <h4 class="fw-bold mb-3">📁 File Preview</h4>

                    {{-- IMAGE PREVIEW --}}
                    @if($isImage)
                    <img src="{{ asset('storage/'.$link->file_path) }}" class="img-fluid rounded mb-3" style="max-height:320px">
                    @endif

                    {{-- VIDEO PREVIEW --}}
                    @if($isVideo)
                    <video controls class="rounded mb-3 w-100" style="max-height:320px">
                        <source src="{{ asset('storage/'.$link->file_path) }}">
                        Your browser does not support video playback.
                    </video>
                    @endif

                    <p class="text-muted mb-2">
                        👁️ Views: {{ $link->clicks }}
                    </p>

                    <p class="text-muted mb-3">
                        ⬇ Downloads: {{ $link->downloads ?? 0 }}
                    </p>

                    <a href="{{ route('file.download', $link->short_code) }}" class="btn btn-primary w-100">
                        ⬇ Download File
                    </a>

                </div>

            </div>
        </div>
    </div>

</body>
</html>
