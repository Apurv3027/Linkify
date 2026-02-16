@extends('admin.layouts.app')

@section('title','Settings')
@section('page-title','Admin Settings')

@section('content')

<div class="card shadow-sm p-4">

    <form>
        <div class="mb-3">
            <label class="form-label">Platform Name</label>
            <input type="text" class="form-control" value="Linkify">
        </div>

        <div class="mb-3">
            <label class="form-label">Admin Email</label>
            <input type="email" class="form-control" value="{{ auth()->user()->email }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Maintenance Mode</label>
            <select class="form-select">
                <option>Disable</option>
                <option>Enable</option>
            </select>
        </div>

        <button class="btn btn-primary">
            Save Changes
        </button>
    </form>

</div>

@endsection
