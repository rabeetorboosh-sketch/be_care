<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{
    public function index()
    {
        $deviceTypes = DeviceType::all();
        return view('device_types.index', compact('deviceTypes'));
    }

    public function create()
    {
        session(['previous_url' => url()->previous()]);
        return view('device_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
        ]);

        DeviceType::create($request->all());

        $redirectTo = session('previous_url', route('device-types.index'));

        return redirect($redirectTo)->with('success', 'تم إضافة نوع الجهاز بنجاح.');
    }

    public function edit(DeviceType $deviceType)
    {
        return view('device_types.edit', compact('deviceType'));
    }

    public function update(Request $request, DeviceType $deviceType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
        ]);

        $deviceType->update($request->all());

        return redirect()->route('device-types.index')
            ->with('success', 'تم تعديل نوع الجهاز بنجاح.');
    }

    public function destroy(DeviceType $deviceType)
    {
        $deviceType->delete();
        return redirect()->route('device-types.index')
            ->with('success', 'تم حذف نوع الجهاز بنجاح.');
    }
}
