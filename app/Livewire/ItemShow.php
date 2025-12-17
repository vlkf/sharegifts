<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;

class ItemShow extends Component
{
    public Item $item;

    public function mount(Item $item): void
    {
        $this->item = $item->load(['photos']);
    }

    public function render()
    {
        return view('livewire.item-show');
    }
}
