<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ItemsFeed extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';
    
    /** Filters */
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'cat')]
    public ?int $categoryId = null;

    #[Url(as: 'city')]
    public string $city = '';

    #[Url(as: 'status')]
    public string $status = 'available';

    #[Url(as: 'sort')]
    public string $sort = 'newest';

    public $categories = [];

    public function mount(): void
    {
        $this->categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function updatedSearch(): void 
    { 
        $this->resetPage(); 
    }

    public function updatedCategoryId(): void 
    { 
        $this->resetPage(); 
    }

    public function updatedCity(): void 
    { 
        $this->resetPage(); 
    }

    public function updatedStatus(): void 
    { 
        $this->resetPage(); 
    }

    public function updatedSort(): void 
    { 
        $this->resetPage(); 
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'categoryId', 'city']);
        $this->status = 'available';
        $this->sort = 'newest';
        $this->resetPage();
    }

    public function render(): View
    {
        $items = Item::query()
            ->with([
                'category:id,name',
                'user:id,name',
                'photos:id,item_id,path',
            ])
            ->when($this->status !== 'all', function (Builder $q) {
                $q->where('status', $this->status);
            })
            ->when($this->categoryId, fn (Builder $q) => $q->where('category_id', $this->categoryId))
            ->when(trim($this->city) !== '', fn (Builder $q) => $q->where('city', 'like', '%'.trim($this->city).'%'))
            ->when(trim($this->search) !== '', function (Builder $q) {
                $term = '%'.trim($this->search).'%';
                $q->where(function (Builder $qq) use ($term) {
                    $qq->where('title', 'like', $term)
                       ->orWhere('description', 'like', $term);
                });
            })
            ->when($this->sort === 'top', function (Builder $q) {
                $q->orderByDesc('score')->orderByDesc('created_at');
            }, function (Builder $q) {
                $q->orderByDesc('created_at');
            })
            ->paginate(12);

        return view('livewire.items-feed', [
            'items' => $items,
        ]);
    }
}
