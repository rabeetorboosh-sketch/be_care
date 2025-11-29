<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * عرض جميع الوحدات
     */
    public function index()
    {
        $units = Unit::orderBy('id', 'desc')->get();
        return view('units.index', compact('units'));
    }

    /**
     * صفحة إنشاء وحدة
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * حفظ وحدة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')->with('success', 'تم إضافة الوحدة بنجاح.');
    }

    /**
     * صفحة التعديل
     */
    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    /**
     * تحديث الوحدة
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'تم تعديل الوحدة بنجاح.');
    }

    /**
     * حذف الوحدة
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'تم حذف الوحدة بنجاح.');
    }
}
