<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemPhoto;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ItemForm extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public ?Item $item = null;

    public string $title = '';
    public string $description = '';
    public string $city = '';
    public ?int $category_id = null;

    public ?string $weight = null;
    public ?string $dimensions = null;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $photos = [];

    public function mount(?int $itemId = null): void
    {
        if (!$itemId) {
            return;
        }

        $item = Item::query()->with('photos')->findOrFail($itemId);

        $this->authorize('update', $item);

        $this->item = $item->load('photos');

        $this->title = $item->title;
        $this->description = $item->description;
        $this->city = $item->city;
        $this->category_id = $item->category_id;
        $this->weight = $item->weight?->format(2) ?? null;
        $this->dimensions = $item->dimensions;
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:120'],
            'description' => ['required', 'string'],
            'city' => ['required', 'string', 'min:2', 'max:80'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'string', 'max:120'],
            'photos' => ['array'],
            'photos.*' => ['image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        DB::transaction(function () use ($data) {
            if ($this->item) {
                $this->authorize('update', $this->item);

                $this->item->update([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'city' => $data['city'],
                    'category_id' => $data['category_id'],
                    'weight' => $data['weight'],
                    'dimensions' => $data['dimensions'],
                ]);
            } else {
                $this->item = Item::create([
                    'user_id' => auth()->id(),
                    'category_id' => $data['category_id'],
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'city' => $data['city'],
                    'weight' => $data['weight'],
                    'dimensions' => $data['dimensions'],
                    'status' => 'available',
                ]);
            }

            foreach ($this->photos as $i => $photo) {
                $path = $photo->store("items/{$this->item->id}", 'public');

                ItemPhoto::create([
                    'item_id' => $this->item->id,
                    'path' => $path,
                    'sort_order' => $this->item->photos()->count() + $i,
                ]);
            }

            $this->photos = [];
        });

        session()->flash('success', $this->wasJustCreated()
            ? 'Item created successfully!'
            : 'Item updated successfully!'
        );

        $this->redirectRoute('items.show', $this->item);
    }

    protected function wasJustCreated(): bool
    {
        return $this->item?->wasRecentlyCreated ?? false;
    }

    public function deletePhoto(int $photoId): void
    {
        if (! $this->item) return;

        $this->authorize('update', $this->item);

        $photo = $this->item->photos()->whereKey($photoId)->firstOrFail();

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        $this->item->refresh();
        session()->flash('success', 'Photo removed.');
    }

    public function markGifted(): void
    {
        if (! $this->item) return;

        $this->authorize('update', $this->item);

        $this->item->update(['status' => 'gifted']);

        session()->flash('success', 'Marked as gifted.');
        $this->redirectRoute('items.show', $this->item);
    }

    public function render()
    {
        return view('livewire.item-form', [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
