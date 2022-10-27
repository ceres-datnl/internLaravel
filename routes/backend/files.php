<?php

use App\Http\Controllers\Backend\FileController;
use Tabuna\Breadcrumbs\Trail;

Route::group(['prefix' => 'files', 'as' => 'files.'],function (){
    Route::post('/uploadImageNews', [FileController::class, 'uploadImageNews'])
        ->name('uploadImageNews');
});
