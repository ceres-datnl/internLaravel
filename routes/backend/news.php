<?php

use App\Http\Controllers\Backend\NewsController;
use Tabuna\Breadcrumbs\Trail;

Route::group(['prefix' => 'news', 'as' => 'news.'],function(){
    Route::get('/', [NewsController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('List News'), route('admin.news.index'));
        });
    Route::get('add', [NewsController::class, 'add'])
        ->name('add')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Add News'), route('admin.news.add'));
        });
    Route::post('store', [NewsController::class, 'store'])
        ->name('store');
    Route::get('view', [NewsController::class, 'view'])
        ->name('view')->breadcrumbs(function (Trail $trail) {
            $trail->push(__('View'), route('admin.news.view'));
        });
    Route::get('edit', [NewsController::class, 'edit'])
        ->name('edit')->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Edit'), route('admin.news.edit'));
        });
    Route::put('update', [NewsController::class, 'update'])
        ->name('update')->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Update'), route('admin.news.update'));
        });
    Route::get('delete', [NewsController::class, 'delete'])
        ->name('delete')->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Delete'), route('admin.news.delete'));
        });
    Route::get('ajax', [NewsController::class, 'ajax'])
        ->name('ajax');
    Route::post('loadCategory', [NewsController::class, 'loadCategory'])
        ->name('loadCategory');
});
