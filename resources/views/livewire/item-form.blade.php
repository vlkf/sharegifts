<div>
    <form wire:submit.prevent="save" class="needs-validation" novalidate>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Title</label>
                <input type="text"
                       class="form-control @error('title') is-invalid @enderror"
                       wire:model.live="title">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea rows="5"
                          class="form-control @error('description') is-invalid @enderror"
                          wire:model.live="description"></textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Category</label>
                <select class="form-select @error('category_id') is-invalid @enderror"
                        wire:model.live="category_id">
                    <option value="">Select category...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">City</label>
                <input type="text"
                       class="form-control @error('city') is-invalid @enderror"
                       wire:model.live="city">
                @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Weight (optional, kg)</label>
                <input type="number" step="0.01"
                       class="form-control @error('weight') is-invalid @enderror"
                       wire:model.live="weight">
                @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Dimensions (optional)</label>
                <input type="text"
                       class="form-control @error('dimensions') is-invalid @enderror"
                       placeholder="e.g. 40x30x20 cm"
                       wire:model.live="dimensions">
                @error('dimensions') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Photos (optional)</label>
                <input type="file"
                       class="form-control @error('photos.*') is-invalid @enderror"
                       wire:model="photos"
                       multiple accept="image/*">
                @error('photos.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                <div class="form-text">You can upload multiple images. Max 2MB each.</div>
            </div>

            @if(!empty($photos))
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($photos as $photo)
                            <img src="{{ $photo->temporaryUrl() }}"
                                 class="rounded border"
                                 style="width: 90px; height: 90px; object-fit: cover;"
                                 alt="Preview">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($item && $item->photos->count())
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-semibold">Existing photos</div>
                        <div class="text-muted small">Click remove to delete</div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach($item->photos as $p)
                            <div class="position-relative">
                                <img src="{{ asset('storage/'.$p->path) }}"
                                     class="rounded border"
                                     style="width: 90px; height: 90px; object-fit: cover;"
                                     alt="Photo">

                                <button type="button"
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                        wire:click="deletePhoto({{ $p->id }})"
                                        wire:confirm="Remove this photo?">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ $item ? 'Save changes' : 'Create item' }}
                </button>

                <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>

            @if($item && $item->status !== 'gifted')
                <button type="button" class="btn btn-outline-success"
                        wire:click="markGifted"
                        wire:confirm="Mark this item as gifted?">
                    Mark as gifted
                </button>
            @endif
        </div>

        <div class="mt-2" wire:loading wire:target="photos,save,deletePhoto,markGifted">
            <div class="text-muted small">Working...</div>
        </div>
    </form>
</div>
