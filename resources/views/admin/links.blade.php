@extends('admin.layouts.app')

@section('title','All Links')
@section('page-title','Links Management')

@section('content')

<div class="card border-0 shadow-sm bg-body rounded-3">

    {{-- Header --}}
    <div class="card-header bg-body-tertiary border-0 py-3 px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h5 class="fw-semibold mb-1 text-body">All Links</h5>
                <small class="text-secondary">
                    Monitor and manage shortened URLs and uploaded files
                </small>
            </div>

            <form method="GET" class="d-flex align-items-center">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search links...">
                    <button class="btn btn-outline-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Table --}}
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">

                <thead class="border-bottom bg-body-tertiary">
                    <tr class="small text-uppercase text-secondary">
                        <th class="ps-4">#</th>
                        <th>User</th>
                        <th>Resource</th>
                        <th>Type</th>
                        <th>Code</th>
                        <th>Clicks</th>
                        <th>Created</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($links as $link)
                    <tr>

                        <td class="ps-4 text-secondary">
                            {{ $loop->iteration }}
                        </td>

                        {{-- User --}}
                        <td>
                            <div class="fw-semibold text-body">
                                {{ $link->user->name ?? 'N/A' }}
                            </div>
                        </td>

                        {{-- Resource --}}
                        <td style="max-width:280px">

                            @if($link->type === 'url')

                            <a href="{{ $link->original_url }}" target="_blank"
                                class="text-decoration-none text-body text-truncate d-inline-flex align-items-center gap-1">

                                <i class="fa fa-link text-primary small"></i>
                                <span class="text-truncate">
                                    {{ $link->original_url }}
                                </span>
                            </a>

                            @else

                            <button type="button"
                                class="btn btn-sm btn-outline-secondary viewFileBtn w-100 text-start text-truncate"
                                data-file="{{ asset('storage/'.$link->file_path) }}">

                                <i class="fa fa-file-lines me-1 small"></i>
                                {{ basename($link->file_path) }}
                            </button>

                            @endif

                        </td>

                        {{-- Type --}}
                        <td>
                            @if($link->type === 'url')
                            <span class="badge bg-info-subtle text-info px-3 py-2 fw-normal">
                                URL
                            </span>
                            @else
                            <span class="badge bg-warning-subtle text-warning px-3 py-2 fw-normal">
                                File
                            </span>
                            @endif
                        </td>

                        {{-- Code --}}
                        <td>
                            <span class="badge bg-success-subtle text-success px-3 py-2 fw-normal">
                                {{ $link->short_code }}
                            </span>
                        </td>

                        {{-- Clicks --}}
                        <td>
                            <span class="fw-semibold text-body">
                                {{ $link->clicks }}
                            </span>
                        </td>

                        {{-- Created --}}
                        <td>
                            <small class="text-secondary">
                                {{ $link->created_at->format('d M Y') }}
                            </small>
                        </td>

                        {{-- Action --}}
                        <td class="text-end pe-4">

                            <div class="d-inline-flex gap-2">

                                {{-- Copy --}}
                                <button class="btn btn-sm btn-outline-primary copyBtn"
                                    data-link="{{ url($link->short_code) }}" title="Copy Short Link">
                                    <i class="fa fa-copy"></i>
                                </button>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="text-center py-5 text-secondary">
                            No links available
                        </td>
                    </tr>

                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-top bg-body d-flex justify-content-between align-items-center flex-wrap gap-2">

            <small class="text-secondary">
                Showing {{ $links->firstItem() ?? 0 }}
                to {{ $links->lastItem() ?? 0 }}
                of {{ $links->total() }} results
            </small>

            {{ $links->links() }}

        </div>

    </div>
</div>


{{-- File Preview Modal --}}
<div class="modal fade" id="fileModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-body border-0 shadow rounded-3">

            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold">File Preview</h6>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <iframe id="fileFrame" src="" style="width:100%; height:75vh; border:0;">
                </iframe>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('click', function (e) {

    // File preview
    const fileBtn = e.target.closest('.viewFileBtn');
    if (fileBtn) {
        document.getElementById('fileFrame').src = fileBtn.dataset.file;
        new bootstrap.Modal(document.getElementById('fileModal')).show();
        return;
    }

    // Copy short link
    const copyBtn = e.target.closest('.copyBtn');
    if (copyBtn) {

        navigator.clipboard.writeText(copyBtn.dataset.link);

        copyBtn.innerHTML = '<i class="fa fa-check"></i>';

        setTimeout(() => {
            copyBtn.innerHTML = '<i class="fa fa-copy"></i>';
        }, 1500);
    }

});

document.getElementById('fileModal')
    .addEventListener('hidden.bs.modal', function () {
        document.getElementById('fileFrame').src = '';
});

</script>
@endpush
