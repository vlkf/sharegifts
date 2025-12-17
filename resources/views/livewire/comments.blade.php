<div>
    <form wire:submit.prevent="add" class="mb-3">
        <label class="form-label">Add a comment</label>

        <textarea rows="3"
                  class="form-control @error('body') is-invalid @enderror"
                  wire:model.live="body"
                  placeholder="Write something..."></textarea>

        @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror

        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn btn-sm btn-primary">
                Post comment
            </button>
        </div>
    </form>

    <div class="d-flex flex-column gap-2">
        @forelse($comments as $comment)
            <div class="card">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div class="fw-semibold">{{ $comment->user->name }}</div>
                        <div class="text-muted small">{{ $comment->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="text-body-secondary" style="white-space: pre-wrap;">{{ $comment->body }}</div>
                </div>
            </div>
        @empty
            <div class="text-muted">No comments yet.</div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $comments->links() }}
    </div>
</div>
