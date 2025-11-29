<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Unit;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * عرض جميع الأصناف
     */
    public function index()
    {
        $items = Item::with('units.unit')->orderBy('id', 'desc')->get();
        return view('items.index', compact('items'));
    }

    /**
     * صفحة إنشاء صنف جديد
     */
    public function create()
    {
        $units = Unit::all();
        return view('items.create', compact('units'));
    }

    /**
     * حفظ صنف جديد مع وحداته
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:new,used,refurbished',
            'units.*.unit_id' => 'required|exists:units,id',
            'units.*.qty' => 'required|numeric|min:0.01',
        ]);

        $item = Item::create($request->only([
            'name', 'company', 'version', 'specs', 'type', 'status', 'notes'
        ]));

        if ($request->has('units')) {
            foreach ($request->units as $unitData) {
                $item->units()->create([
                    'unit_id' => $unitData['unit_id'],
                    'qty' => $unitData['qty'],
                ]);
            }
        }

        return redirect()->route('items.index')->with('success', 'تم إضافة الصنف مع وحداته بنجاح.');
    }

    /**
     * صفحة تعديل الصنف
     */
    public function edit(Item $item)
    {
        $units = Unit::all();
        $item->load('units');
        return view('items.edit', compact('item', 'units'));
    }

    /**
     * تحديث الصنف مع وحداته
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:new,used,refurbished',
            'units.*.unit_id' => 'required|exists:units,id',
            'units.*.qty' => 'required|numeric|min:0.01',
        ]);

        $item->update($request->only([
            'name', 'company', 'version', 'specs', 'type', 'status', 'notes'
        ]));

        // حذف الوحدات القديمة
        $item->units()->delete();

        // إنشاء الوحدات الجديدة
        if ($request->has('units')) {
            foreach ($request->units as $unitData) {
                $item->units()->create([
                    'unit_id' => $unitData['unit_id'],
                    'qty' => $unitData['qty'],
                ]);
            }
        }

        return redirect()->route('items.index')->with('success', 'تم تعديل الصنف بنجاح.');
    }
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }
    /**
     * حذف الصنف مع وحداته
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'تم حذف الصنف بنجاح.');
    }
}
