<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('items.index')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');


    //Route::get('/my-items', [ItemController::class, 'mine'])->name('items.mine');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
