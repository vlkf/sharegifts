@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h3 mb-1">Post an item</h1>
        <div class="text-muted">Share something you don’t need anymore.</div>
    </div>

    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
        Cancel
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <livewire:item-form />
    </div>
</div>
@endsection
