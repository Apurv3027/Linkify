@extends('admin.layouts.app')

@section('title','Reports')
@section('page-title','System Reports')

@section('content')

<div class="row g-4">

    <div class="col-md-4">
        <div class="card shadow text-center p-4">
            <h5>Total Users</h5>
            <h2>{{ \App\Models\User::count() }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow text-center p-4">
            <h5>Total Links</h5>
            <h2>{{ \App\Models\Link::count() }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow text-center p-4">
            <h5>Total Clicks</h5>
            <h2>{{ \App\Models\Click::count() }}</h2>
        </div>
    </div>

</div>

@endsection
