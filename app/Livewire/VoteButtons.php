<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VoteButtons extends Component
{
    public Item $item;

    public int $score = 0;
    public int $myVote = 0; // -1, 0, +1

    public function mount(Item $item): void
    {
        $this->item = $item;
        $this->refreshState();
    }

    public function vote(int $value): void
    {
        abort_unless(in_array($value, [-1, 1], true), 400);
        abort_unless(auth()->check(), 403);

        DB::transaction(function () use ($value) {
            $existing = Vote::query()
                ->where('item_id', $this->item->id)
                ->where('user_id', auth()->id())
                ->lockForUpdate()
                ->first();

            $old = $existing?->value ?? 0;
            $new = ($old === $value) ? 0 : $value; // toggle off 

            if ($existing && $new === 0) {
                $existing->delete();
            } elseif ($existing) {
                $existing->update(['value' => $new]);
            } else {
                Vote::create([
                    'item_id' => $this->item->id,
                    'user_id' => auth()->id(),
                    'value' => $new,
                ]);
            }

            $delta = $new - $old;
            if ($delta !== 0) {
                $this->item->increment('score', $delta);
            }

            $this->item->refresh();
        });

        $this->refreshState();
    }

    protected function refreshState(): void
    {
        $this->score = (int) $this->item->score;

        $this->myVote = auth()->check()
            ? (int) Vote::query()
                ->where('item_id', $this->item->id)
                ->where('user_id', auth()->id())
                ->value('value') ?? 0
            : 0;
    }

    public function render()
    {
        return view('livewire.vote-buttons');
    }
}
