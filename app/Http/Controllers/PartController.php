<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::all();
        return view('parts.index', compact('parts'));
    }

    public function create()
    {
        return view('parts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'purchase_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'is_active' => 'required|boolean',
        ]);

        Part::create($request->all());

        return redirect()->route('parts.index')->with('success', 'تم إضافة القطعة بنجاح.');
    }

    public function edit(Part $part)
    {
        return view('parts.edit', compact('part'));
    }

    public function update(Request $request, Part $part)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'purchase_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'is_active' => 'required|boolean',
        ]);

        $part->update($request->all());

        return redirect()->route('parts.index')->with('success', 'تم تعديل القطعة بنجاح.');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'تم حذف القطعة بنجاح.');
    }
}
