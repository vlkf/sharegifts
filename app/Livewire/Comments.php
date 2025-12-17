<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public Item $item;
    public string $body = '';

    public function mount(Item $item): void
    {
        $this->item = $item;
    }

    protected function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ];
    }

    public function add(): void
    {
        abort_unless(auth()->check(), 403);

        $data = $this->validate();

        Comment::create([
            'item_id' => $this->item->id,
            'user_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        $this->reset('body');
        $this->resetPage();

        session()->flash('success', 'Comment added.');
    }

    public function render()
    {
        $comments = Comment::query()
            ->where('item_id', $this->item->id)
            ->with('user:id,name')
            ->latest()
            ->paginate(6);

        return view('livewire.comments', [
            'comments' => $comments,
        ]);
    }
}
