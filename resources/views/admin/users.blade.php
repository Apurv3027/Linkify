@extends('admin.layouts.app')

@section('title','Users')
@section('page-title','Users Management')

@section('content')

<div class="card border-0 shadow-sm">

    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-semibold">All Users</h5>
            <small class="text-muted">Manage registered platform users</small>
        </div>

        <input type="text" id="searchInput" class="form-control form-control-sm w-auto" placeholder="Search users...">
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    @include('admin.components.user_table')
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $users->links() }}
        </div>

    </div>
</div>

{{-- User Detail Modal --}}
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modalName"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Joined:</strong> <span id="modalDate"></span></p>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const userTable = document.getElementById('userTable');

    // Live search (on text change)
    searchInput.addEventListener('keyup', function () {

        fetch(`?search=${this.value}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(data => {
            userTable.innerHTML = data;
        });
    });

    // View user modal popup
    document.addEventListener('click', function (e) {
        if (e.target.closest('.viewUserBtn')) {

            const btn = e.target.closest('.viewUserBtn');

            document.getElementById('modalName').innerText = btn.dataset.name;
            document.getElementById('modalEmail').innerText = btn.dataset.email;
            document.getElementById('modalDate').innerText = btn.dataset.date;

            new bootstrap.Modal(document.getElementById('userModal')).show();
        }
    });
</script>
@endpush
