<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Customer;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::with(['type', 'customer'])->get();
        return view('devices.index', compact('devices'));
    }

    public function create()
    {
        $types = DeviceType::all();
        $customers = Customer::all();
        return view('devices.create', compact('types', 'customers'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'device_type_id' => 'required|exists:device_types,id',
            'customer_id' => 'required|exists:customers,id',
            'device_code' => 'required|unique:devices',
            'notes' => 'nullable|string',
        ]);

        Device::create($request->all());

        return redirect()->route('devices.index')->with('success', 'تم إضافة الجهاز بنجاح.');
    }

    public function edit(Device $device)
    {
        $types = DeviceType::all();
        $customers = Customer::all();
        return view('devices.edit', compact('device', 'types', 'customers'));
    }

    public function update(Request $request, Device $device)
    {
        $request->validate([
            'device_type_id' => 'required|exists:device_types,id',
            'customer_id' => 'required|exists:customers,id',
            'device_code' => "required|string|unique:devices,device_code,{$device->id}",
            'notes' => 'nullable|string',
        ]);

        $device->update($request->all());

        return redirect()->route('devices.index')->with('success', 'تم تعديل الجهاز بنجاح.');
    }
    public function show(Device $device)
    {
        $device->load(['type', 'customer', 'invoices']); // تحميل العلاقات
        return view('devices.show', compact('device'));
    }
    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'تم حذف الجهاز بنجاح.');
    }

}
