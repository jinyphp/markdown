<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::post('/upload-image', [
    \Jiny\Markdown\Http\Controllers\QuillUploadImage::class,
    'uploadImage'
    ])->middleware(['web']);


// Route::middleware(['web'])
// ->name('/upload-image.')
// ->prefix('/upload-image')->group(function () {
//     Route::get('{any}', [
//         \Jiny\Markdown\Http\Controllers\QuillUploadImage::class,
//         'uploadImage'])->where('any', '.*');
// });
