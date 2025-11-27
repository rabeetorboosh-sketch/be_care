<?php

use App\Http\Controllers\CashBoxController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseInvoiceController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReceiptOutController;
use App\Http\Controllers\RepairInvoiceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Models\Device;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('customers', CustomerController::class);
    Route::resource('device-types', DeviceTypeController::class);
    Route::resource('parts', PartController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('devices', DeviceController::class);
    Route::resource('cash_boxes', CashBoxController::class);
    Route::resource('repair_invoices', RepairInvoiceController::class);
    Route::resource('receipts', ReceiptController::class);
    Route::resource('receipts_out', ReceiptOutController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('purchase_invoices', PurchaseInvoiceController::class);
    Route::get('/api/customer-devices/{customer_id}', function($customer_id){
        return response()->json(Device::where('customer_id', $customer_id)->with('type')->get());
    });
    Route::get('/receipts/accountables/{type}', [ReceiptController::class, 'getAccountables']);
});

require __DIR__.'/auth.php';
