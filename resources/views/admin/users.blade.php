@extends('admin.layouts.app')

@section('title','Users')
@section('page-title','Users Management')

@section('content')

<div class="card border-0 shadow-sm bg-body">

    {{-- Header --}}
    <div
        class="card-header bg-body-tertiary border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-0 fw-semibold text-body">All Users</h5>
            <small class="text-secondary">
                Manage registered platform users
            </small>
        </div>

        <form method="GET" class="d-flex">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm me-2"
                placeholder="Search users...">
            <button class="btn btn-sm btn-outline-primary">
                <i class="fa fa-search"></i>
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="border-bottom">
                    <tr class="text-secondary">
                        <th>#</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @include('admin.components.user_table')
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-3 border-top">
            {{ $users->links() }}
        </div>

    </div>
</div>


{{-- USER DETAILS MODAL --}}
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body border-0 shadow">

            <div class="modal-header border-bottom">
                <h5 class="modal-title">User Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-body">
                <div class="mb-3">
                    <small class="text-secondary">Name</small>
                    <div class="fw-semibold" id="modalName"></div>
                </div>

                <div class="mb-3">
                    <small class="text-secondary">Email</small>
                    <div id="modalEmail"></div>
                </div>

                <div>
                    <small class="text-secondary">Joined On</small>
                    <div id="modalDate"></div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
    // View User Modal
    document.addEventListener('click', function (e) {

        const btn = e.target.closest('.viewUserBtn');

        if (!btn) return;

        document.getElementById('modalName').innerText = btn.dataset.name;
        document.getElementById('modalEmail').innerText = btn.dataset.email;
        document.getElementById('modalDate').innerText = btn.dataset.date;

        new bootstrap.Modal(document.getElementById('userModal')).show();
    });

</script>
@endpush
