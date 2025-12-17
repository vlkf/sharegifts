<div>
    @if($item->photos->count())
        <div id="itemPhotosCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
            <div class="carousel-inner rounded border">
                @foreach($item->photos as $idx => $p)
                    <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/'.$p->path) }}"
                             class="d-block w-100"
                             style="height: 360px; object-fit: cover;"
                             alt="Photo">
                    </div>
                @endforeach
            </div>

            @if($item->photos->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#itemPhotosCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#itemPhotosCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h2 class="h6 mb-2">Description</h2>
            <div class="text-body-secondary" style="white-space: pre-wrap;">{{ $item->description }}</div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="fw-semibold">Voting</div>
            <livewire:vote-buttons :item="$item" :key="'votes-'.$item->id" />
        </div>
    </div>
</div>
