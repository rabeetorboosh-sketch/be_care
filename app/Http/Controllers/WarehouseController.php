<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    // عرض جميع المخازن
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('warehouses.index', compact('warehouses'));
    }

    // عرض صفحة إضافة مخزن جديد
    public function create()
    {
        return view('warehouses.create');
    }

    // حفظ المخزن الجديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'is_main' => 'nullable|boolean',
        ]);

        // إذا تم تعيين المخزن كـ رئيسي، اجعل كل المخازن الأخرى غير رئيسية
        if ($request->is_main) {
            Warehouse::query()->update(['is_main' => false]);
        }

        Warehouse::create($request->all());

        return redirect()->route('warehouses.index')->with('success', 'تم إضافة المخزن بنجاح');
    }

    // عرض صفحة تعديل المخزن
    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    // تحديث بيانات المخزن
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'is_main' => 'nullable|boolean',
        ]);

        if ($request->is_main) {
            Warehouse::query()->update(['is_main' => false]);
        }

        $warehouse->update($request->all());

        return redirect()->route('warehouses.index')->with('success', 'تم تحديث المخزن بنجاح');
    }

    // حذف المخزن
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'تم حذف المخزن بنجاح');
    }
}
