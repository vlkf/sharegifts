@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('items.index') }}" class="text-decoration-none">
        ← Back to feed
    </a>
</div>

<div class="d-flex justify-content-between align-items-start gap-3 mb-3">
    <div>
        <h1 class="h3 mb-1">{{ $item->title }}</h1>

        <div class="text-muted">
            {{ $item->city }} • {{ $item->category->name }} • posted by {{ $item->user->name }}
        </div>

        <div class="mt-2">
            @if ($item->status === 'gifted')
                <span class="badge text-bg-success">Gifted</span>
            @else
                <span class="badge text-bg-primary">Available</span>
            @endif
        </div>
    </div>

    <div class="d-flex gap-2">
        @can('update', $item)
            <a href="{{ route('items.edit', $item) }}" class="btn btn-outline-primary">
                Edit
            </a>
        @endcan
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <livewire:item-show :item="$item" />
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 mb-3">Details</h2>

                <dl class="row mb-0">
                    <dt class="col-4 text-muted">Category</dt>
                    <dd class="col-8">{{ $item->category->name }}</dd>

                    <dt class="col-4 text-muted">City</dt>
                    <dd class="col-8">{{ $item->city }}</dd>

                    @if($item->weight)
                        <dt class="col-4 text-muted">Weight</dt>
                        <dd class="col-8">{{ $item->weight }} kg</dd>
                    @endif

                    @if($item->dimensions)
                        <dt class="col-4 text-muted">Dimensions</dt>
                        <dd class="col-8">{{ $item->dimensions }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h6 mb-3">Comments</h2>
                <livewire:comments :item="$item" />
            </div>
        </div>
    </div>
</div>
@endsection
