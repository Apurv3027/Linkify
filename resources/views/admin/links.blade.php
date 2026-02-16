@extends('admin.layouts.app')

@section('title','All Links')
@section('page-title','All Links')

@section('content')

<div class="card shadow-sm">
    <div class="card-body table-responsive">

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Original URL</th>
                    <th>Short Code</th>
                    <th>Clicks</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($links as $link)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $link->user->name ?? 'N/A' }}</td>
                    <td class="text-truncate" style="max-width:200px;">
                        {{ $link->original_url }}
                    </td>
                    <td>
                        <span class="badge bg-success">
                            {{ $link->short_code }}
                        </span>
                    </td>
                    <td>{{ $link->clicks }}</td>
                    <td>{{ $link->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $links->links() }}

    </div>
</div>

@endsection
