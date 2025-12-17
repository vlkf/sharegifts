<div class="d-flex align-items-center gap-2">
    <div class="btn-group" role="group" aria-label="Voting buttons">
        <button type="button"
                class="btn btn-sm {{ $myVote === 1 ? 'btn-success' : 'btn-outline-success' }}"
                wire:click="vote(1)">
            ▲
        </button>

        <button type="button"
                class="btn btn-sm {{ $myVote === -1 ? 'btn-danger' : 'btn-outline-danger' }}"
                wire:click="vote(-1)">
            ▼
        </button>
    </div>

    <span class="badge text-bg-secondary">
        Score: {{ $score }}
    </span>
</div>
