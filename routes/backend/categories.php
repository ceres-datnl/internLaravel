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
    Route::get('delete', [CategoryController::class, 'delete'])
        ->name('delete');
    Route::get('ajaxLoadListCategory', [CategoryController::class, 'ajaxLoadListCategory'])
        ->name('ajaxLoadListCategory');
    Route::get('view/{id}', [CategoryController::class, 'view'])
        ->name('view');
    Route::post('store', [CategoryController::class, 'store'])
        ->name('store');
    Route::get('edit/{id}', [CategoryController::class, 'edit'])
        ->name('edit');
    Route::put('update/{id}', [CategoryController::class, 'update'])->name('update');
});



