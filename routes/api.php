<?php

use Illuminate\Support\Facades\Route;
use App\Models\Device;

Route::get('/customer-devices/{customer_id}', function ($customer_id) {
    dd(Device::all());
    return Device::where('customer_id', $customer_id)->get();
});
