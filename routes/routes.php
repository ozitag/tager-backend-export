<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['provider:administrators', 'auth:api']], function () {
});
