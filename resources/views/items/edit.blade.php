@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h3 mb-1">Edit item</h1>
        <div class="text-muted">Update details or mark it as gifted.</div>
    </div>

    <a href="{{ route('items.show', $item) }}" class="btn btn-outline-secondary">
        Back
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <livewire:item-form :item="$item->id" />
    </div>
</div>
@endsection
