<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('asset', 'AssetCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('borrowing', 'BorrowingCrudController');
    Route::crud('user', 'UserCrudController');
    Route::get('charts/asset-status-chart', 'Charts\AssetStatusChartController@response')->name('charts.asset-status-chart.index');
    Route::get('charts/asset-category-chart', 'Charts\AssetCategoryChartController@response')->name('charts.asset-category-chart.index');
    Route::get('charts/user-borrowed', 'Charts\UserBorrowedChartController@response')->name('charts.user-borrowed.index');
    Route::get('borrowing/update', 'CustomController@updateBorrowerAndAssetStatus');
    Route::get('borrowing/update', 'CustomController@updateBorrowerAndAssetStatus');
}); // this should be the absolute last line of this file
