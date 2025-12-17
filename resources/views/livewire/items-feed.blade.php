<div>
    {{-- Filters --}}
    <div class="row g-2 align-items-end mb-3">
        <div class="col-12 col-md-5">
            <label class="form-label mb-1">Search</label>
            <input
                type="text"
                class="form-control"
                placeholder="Search by title or description..."
                wire:model.live.debounce.400ms="search"
            >
        </div>

        <div class="col-12 col-md-3">
            <label class="form-label mb-1">Category</label>
            <select class="form-select" wire:model.live="categoryId">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2">
            <label class="form-label mb-1">City</label>
            <input
                type="text"
                class="form-control"
                placeholder="e.g. Sofia"
                wire:model.live.debounce.400ms="city"
            >
        </div>

        <div class="col-6 col-md-1">
            <label class="form-label mb-1">Status</label>
            <select class="form-select" wire:model.live="status">
                <option value="available">Available</option>
                <option value="gifted">Gifted</option>
                <option value="all">All</option>
            </select>
        </div>

        <div class="col-6 col-md-1">
            <label class="form-label mb-1">Sort</label>
            <select class="form-select" wire:model.live="sort">
                <option value="newest">Newest</option>
                <option value="top">Most upvoted</option>
            </select>
        </div>

        <div class="col-12 d-flex justify-content-between align-items-center mt-2">
            <div class="text-muted small">
                Showing {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} of {{ $items->total() }}
            </div>

            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="clearFilters">
                Reset filters
            </button>
        </div>
    </div>

    {{-- Grid --}}
    <div class="row g-3">
        @forelse($items as $item)

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if($thumbUrl)
                        <img src="{{ asset('storage/'.$photo->path) }}" class="card-img-top" alt="Photo of {{ $item->title }}" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-secondary-subtle d-flex align-items-center justify-content-center" style="height: 180px;">
                            <span class="text-muted">No photo</span>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <h2 class="h6 mb-1">
                                <a class="text-decoration-none" href="{{ route('items.show', $item) }}">
                                    {{ $item->title }}
                                </a>
                            </h2>

                            <span class="badge {{ $item->status === 'gifted' ? 'text-bg-success' : 'text-bg-primary' }}">
                                {{ $item->status === 'gifted' ? 'Gifted' : 'Available' }}
                            </span>
                        </div>

                        <div class="text-muted small mb-2">
                            {{ $item->city }} • {{ $item->category->name }} • by {{ $item->user->name }}
                        </div>

                        <p class="mb-0 text-body-secondary">
                            {{ str($item->description)->limit(120) }}
                        </p>
                    </div>

                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Score: {{ $item->score }}</span>
                            <a href="{{ route('items.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">
                    No items found. Try adjusting filters.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $items->links() }}
    </div>
</div>
