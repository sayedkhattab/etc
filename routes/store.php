<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StoreCaseCategoryController;
use App\Http\Controllers\Admin\StoreCaseFileController;
use App\Http\Controllers\Admin\StoreCasePurchaseController;

/*
|--------------------------------------------------------------------------
| Store Routes
|--------------------------------------------------------------------------
|
| Here is where you can register store routes for your application.
|
*/

// متجر القضايا - إدارة فئات القضايا
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // إدارة فئات القضايا
    Route::get('store/categories', [StoreCaseCategoryController::class, 'index'])->name('store.categories.index');
    Route::get('store/categories/create', [StoreCaseCategoryController::class, 'create'])->name('store.categories.create');
    Route::post('store/categories', [StoreCaseCategoryController::class, 'store'])->name('store.categories.store');
    Route::get('store/categories/{category}', [StoreCaseCategoryController::class, 'show'])->name('store.categories.show');
    Route::get('store/categories/{category}/edit', [StoreCaseCategoryController::class, 'edit'])->name('store.categories.edit');
    Route::put('store/categories/{category}', [StoreCaseCategoryController::class, 'update'])->name('store.categories.update');
    Route::delete('store/categories/{category}', [StoreCaseCategoryController::class, 'destroy'])->name('store.categories.destroy');
    Route::post('store/categories/order', [StoreCaseCategoryController::class, 'updateOrder'])->name('store.categories.order');
    
    // إدارة ملفات القضايا
    Route::get('store/case-files', [StoreCaseFileController::class, 'index'])->name('store.case-files.index');
    Route::get('store/case-files/create', [StoreCaseFileController::class, 'create'])->name('store.case-files.create');
    Route::post('store/case-files', [StoreCaseFileController::class, 'store'])->name('store.case-files.store');
    Route::get('store/case-files/{case_file}', [StoreCaseFileController::class, 'show'])->name('store.case-files.show');
    Route::get('store/case-files/{case_file}/edit', [StoreCaseFileController::class, 'edit'])->name('store.case-files.edit');
    Route::put('store/case-files/{case_file}', [StoreCaseFileController::class, 'update'])->name('store.case-files.update');
    Route::delete('store/case-files/{case_file}', [StoreCaseFileController::class, 'destroy'])->name('store.case-files.destroy');
    Route::post('store/case-files/{case_file}/attachments', [StoreCaseFileController::class, 'addAttachment'])->name('store.case-files.attachments.add');
    Route::delete('store/case-files/{case_file}/attachments/{attachment}', [StoreCaseFileController::class, 'removeAttachment'])->name('store.case-files.attachments.remove');
    Route::post('store/case-files/{case_file}/activate', [StoreCaseFileController::class, 'activate'])->name('store.case-files.activate');
    Route::get('store/case-files/attachments/{attachment}/download', [StoreCaseFileController::class, 'downloadAttachment'])->name('store.case-files.attachments.download');
    
    // إدارة مشتريات القضايا
    Route::get('store/purchases', [StoreCasePurchaseController::class, 'index'])->name('store.purchases.index');
    Route::get('store/purchases/create', [StoreCasePurchaseController::class, 'create'])->name('store.purchases.create');
    Route::post('store/purchases', [StoreCasePurchaseController::class, 'store'])->name('store.purchases.store');
    Route::get('store/purchases/{purchase}', [StoreCasePurchaseController::class, 'show'])->name('store.purchases.show');
    Route::get('store/purchases/{purchase}/edit', [StoreCasePurchaseController::class, 'edit'])->name('store.purchases.edit');
    Route::put('store/purchases/{purchase}', [StoreCasePurchaseController::class, 'update'])->name('store.purchases.update');
    Route::delete('store/purchases/{purchase}', [StoreCasePurchaseController::class, 'destroy'])->name('store.purchases.destroy');
    Route::post('store/purchases/{purchase}/activate', [StoreCasePurchaseController::class, 'activate'])->name('store.purchases.activate');
    Route::get('store/purchases/check-role', [StoreCasePurchaseController::class, 'checkRole'])->name('store.purchases.check-role');
}); 