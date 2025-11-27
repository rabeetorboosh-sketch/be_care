<?php

namespace App\Http\Controllers;

use App\Models\CashBox;
use App\Models\LedgerEntry;
use App\Models\RepairInvoice;
use Illuminate\Http\Request;

class CashBoxController extends Controller
{
    // عرض كل الصناديق
    public function index()
    {
        $cashBoxes = CashBox::all();
        return view('cash_boxes.index', compact('cashBoxes'));
    }

    // نموذج إضافة صندوق جديد
    public function create()
    {
        return view('cash_boxes.create');
    }

    // تخزين صندوق جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'opening_balance' => 'required|numeric',
            'is_active' => 'required|boolean',
            'is_main' => 'required|integer|in:0,1',
        ]);
        if ($request->is_main == 1) {
            CashBox::query()->update(['is_main' => 0]);
        }
        $box =CashBox::create($request->all());

        if($request->opening_balance>0 ){

            LedgerEntry::create([
                'accountable_id' => $box->id,
                'accountable_type' => 'cash_box',
                'description' => "رصيد افتتاحي",
                'debit' =>$request->opening_balance,
                'credit' => 0,
                'reference_id' => 0,
                'reference_type' => CashBox::class,
            ]);
        }
        return redirect()->route('cash_boxes.index')->with('success', 'تم إضافة الصندوق بنجاح.');
    }

    // نموذج تعديل صندوق
    public function edit(CashBox $cashBox)
    {
        return view('cash_boxes.edit', compact('cashBox'));
    }

    // تحديث بيانات الصندوق
    public function update(Request $request, CashBox $cashBox)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_main' => 'required|integer|in:0,1',
        ]);
        if ($request->is_main == 1) {
            CashBox::query()->update(['is_main' => 0]);
        }
        $cashBox->update($request->all());

        return redirect()->route('cash_boxes.index')->with('success', 'تم تعديل الصندوق بنجاح.');
    }

    // حذف صندوق
    public function destroy(CashBox $cashBox)
    {
        $cashBox->delete();
        return redirect()->route('cash_boxes.index')->with('success', 'تم حذف الصندوق بنجاح.');
    }

    // عرض تفاصيل صندوق
    public function show(CashBox $cashBox)
    {
        return view('cash_boxes.show', compact('cashBox'));
    }
}
