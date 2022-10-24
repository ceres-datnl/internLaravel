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
    Route::get('view/{id}', [NewsController::class, 'view'])
        ->name('view');
    Route::get('edit/{id}', [NewsController::class, 'edit'])
        ->name('edit');
    Route::put('update/{id}', [NewsController::class, 'update'])
        ->name('update')->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Update'), route('admin.news.update'));
        });
    Route::get('delete', [NewsController::class, 'delete'])
        ->name('delete');
    Route::get('ajaxLoadListNews', [NewsController::class, 'ajaxLoadListNews'])
        ->name('ajaxLoadListNews');
    Route::post('loadCategory', [NewsController::class, 'loadCategory'])
        ->name('loadCategory');
});
