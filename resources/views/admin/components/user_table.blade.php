@forelse($users as $user)
<tr>

    <td>{{ $loop->iteration }}</td>

    <td>
        <div class="fw-semibold text-body">
            {{ $user->name }}
        </div>
        <small class="text-secondary">
            {{ $user->email }}
        </small>
    </td>

    <td>
        <span class="badge bg-primary-subtle text-primary px-3 py-2">
            User
        </span>
    </td>

    <td>
        <small class="text-secondary">
            {{ $user->created_at->format('d M Y') }}
        </small>
    </td>

    <td class="text-end">
        <button class="btn btn-sm btn-outline-primary viewUserBtn" data-id="{{ $user->id }}"
            data-name="{{ $user->name }}" data-email="{{ $user->email }}"
            data-date="{{ $user->created_at->format('d M Y') }}">

            <i class="fa fa-eye"></i>
        </button>
    </td>

</tr>
@empty
<tr>
    <td colspan="5" class="text-center py-5 text-secondary">
        No users found.
    </td>
</tr>
@endforelse
