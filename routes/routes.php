<?php

use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Export\Controllers\ExportController;

Route::group(['prefix' => 'admin/tager', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::get('/export/strategies', [ExportController::class, 'strategies']);
    Route::get('/export', [ExportController::class, 'index']);
    Route::post('/export', [ExportController::class, 'store']);
    Route::get('/export/{id}/download', [ExportController::class, 'download'])->name('downloadExport');
});
