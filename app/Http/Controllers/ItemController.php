<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return view('items.index');
    }

    public function show(Item $item)
    {
        $item->load(['user', 'category', 'photos']);

        return view('items.show', compact('item'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function edit(Item $item)
    {
        $this->authorize('update', $item);

        return view('items.edit', compact('item'));
    }

    // public function mine()
    // {
    //     return view('items.mine');
    // }
}

