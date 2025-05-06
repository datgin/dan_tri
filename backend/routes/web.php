<?php

use App\Http\Controllers\admin\BulkActionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\Auth\AuthController;
use App\Http\Controllers\admin\CatalogueController;
use App\Http\Controllers\admin\ConfigController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\KeywordController;
use App\Http\Controllers\admin\NewsController;
use App\Http\Controllers\admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/






Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('detail/{id}', [UserController::class, 'getUserInfor'])->name('getUserInfor');
    Route::post('update/{id}', [UserController::class, 'updateUserInfor'])->name('updateUserInfor');
    Route::post('change-password', [UserController::class, 'changePassword'])->name('changePassword');
    route::middleware('admin.guest')->group(function () {
        route::get('login', [AuthController::class, 'login'])->name('login');
        route::post('login', [AuthController::class, 'authenticate']);
    });

    route::middleware('admin.auth')->group(function () {

        // Route::get('/', function () {
        //     $title = ' Dashboard';
        //     return view('backend.dashboard', compact('title'));
        // })->name('dashboard');
        route::get('/', action: [DashboardController::class, 'getRevenueChart'])->name('dashboard');

        route::get('logout', [AuthController::class, 'logout'])->name('logout');

        Route::prefix('catalogues')->name('catalogues.')->group(function () {
            Route::get('/', [CatalogueController::class, 'index'])->name('index');
            Route::post('store', [CatalogueController::class, 'store'])->name('store');
            Route::get('{slug}/edit', [CatalogueController::class, 'edit'])->name('edit');
            
        });

        Route::prefix('keywords')->name('keywords.')->group(function () {
            Route::get('/', [KeywordController::class, 'index'])->name('index');
            Route::post('store', [KeywordController::class, 'store'])->name('store');
            
        });
       
    });


    Route::post('/delete-items', [BulkActionController::class, 'deleteItems'])->name('delete.items');
    Route::post('/change-order', [BulkActionController::class, 'changeOrder'])->name('changeOrder');
    Route::post('/gemini/ask', [BulkActionController::class, 'askGemini'])->name('gemini.ask');


    Route::prefix('news')->name('news.')->controller(NewsController::class)->group(function () {
        Route::get('',  'index')->name('index');
        Route::get('create',  'create')->name('create');
        Route::post('store',  'store')->name('store');
        Route::get('edit/{id}',  'edit')->name('edit');
        Route::post('edit/{id}',  'update')->name('update');
        Route::post('delete/{id}',  'delete')->name('delete');
    });

    Route::prefix('config')->name('config.')->group(function () {
        Route::get('config-support', [ConfigController::class, 'configSupport'])->name('config-support');
        Route::get('', [ConfigController::class, 'index'])->name('index');
        Route::post('update', [ConfigController::class, 'update'])->name('update');
        Route::post('update-support', [ConfigController::class, 'updateSupport'])->name('update-support');
        route::get('config-payment/{id?}', [ConfigController::class, 'configPayment'])->name('config-payment');
        route::post('config-payment', [ConfigController::class, 'configPaymentPost']);
        route::put('config-payment', [ConfigController::class, 'handleChangePublishPayment'])->name('handle-change-publish-payment');
        route::get('config-slider', [ConfigController::class, 'configSlider'])->name('config-slider');
        route::post('config-slider', [ConfigController::class, 'handleSubmitSlider'])->name('handle-submit-slider');
        route::get('config-filter', [ConfigController::class, 'configFilter'])->name('config-filter');
        route::post('config-filter', [ConfigController::class, 'handleSubmitFilter']);
        route::post('config-filter-update/{id}', [ConfigController::class, 'handleSubmitChangeFilter'])->name('config-filter-update');
        route::post('config-transfer-payment', [ConfigController::class, 'configTransferPayment'])->name('config-transfer-payment');
    });
});

