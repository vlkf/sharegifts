@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h3 mb-1">Community gifting board</h1>
        <div class="text-muted">Browse items, vote and comment — all free.</div>
    </div>

    <a href="{{ route('items.create') }}" class="btn btn-primary">
        + Post item
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <livewire:items-feed />
    </div>
</div>
@endsection
