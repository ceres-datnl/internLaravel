<?php

use App\Http\Controllers\Backend\CategoryController;
use Tabuna\Breadcrumbs\Trail;

Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
    Route::get('/', [CategoryController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('List Category'), route('admin.categories.index'));
        });
    Route::get('add', [CategoryController::class, 'add'])
        ->name('add')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Add Category'), route('admin.categories.add'));
        });
    Route::get('view', [CategoryController::class, 'view'])
        ->name('view')->breadcrumbs(function (Trail $trail) {
            $trail->push(__('View'), route('admin.categories.view'));
        });
    Route::post('store', [CategoryController::class, 'store'])
        ->name('store');
    Route::get('edit', [CategoryController::class, 'edit'])
        ->name('edit')->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Edit'), route('admin.categories.edit'));
        });
    Route::put('update',[CategoryController::class, 'update'])->name('update');
});



