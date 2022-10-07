<?php

use App\Http\Controllers\Backend\CategoryController;
use Tabuna\Breadcrumbs\Trail;

Route::get('categories', [CategoryController::class, 'index'])
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('List Category'), route('admin.categories'));
    });
Route::get('categories/create',[CategoryController::class, 'index'])
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Add Category'), route('admin.categories.create'));
    });
