<?php

namespace App\Http\Controllers;

use App\Models\CashBox;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Receipt;
use App\Models\LedgerEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptOutController extends Controller
{
    /** عرض كل سندات الصرف */
    public function index()
    {
        $receipts = Receipt::with('accountable', 'cashBox', 'user')
            ->where('receipt_type', 'out')
            ->latest()
            ->paginate(20);

        return view('receipts_out.index', compact('receipts'));
    }

    /** صفحة الإنشاء */
    public function create()
    {
        $customers = Customer::where('is_active', 1)->get();
        $suppliers = Supplier::all();
        $cashBoxes = CashBox::all();

        return view('receipts_out.create', compact('customers', 'suppliers', 'cashBoxes'));
    }

    /** حفظ سند الصرف */
    public function store(Request $request)
    {
        $request->validate([
            'accountable_id'  => 'required|integer',
            'accountable_type'=> 'required|string',
            'amount'          => 'required|numeric',
            'cash_box_id'     => 'required|integer',
            'description'     => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            $receipt = Receipt::create([
                'receipt_type'    => 'out',   // <-- ثابت
                'accountable_id'  => $request->accountable_id,
                'accountable_type'=> $request->accountable_type,
                'amount'          => $request->amount,
                'cash_box_id'     => $request->cash_box_id,
                'description'     => $request->description,
                'created_by'      => Auth::id(),
            ]);

            /* -------------------------------------------------------
            |   سند صرف = الصندوق دائن + الحساب مدين
            --------------------------------------------------------*/

            // الصندوق (دائن)
            LedgerEntry::create([
                'accountable_id'   => $receipt->cash_box_id,
                'accountable_type' => 'App\\Models\\CashBox',
                'description'      => 'سند صرف #' . $receipt->id,
                'debit'            => 0,
                'credit'           => $receipt->amount,
                'reference_id'     => $receipt->id,
                'reference_type'   => 'App\\Models\\Receipt'
            ]);

            // العميل/المورد (مدين)
            LedgerEntry::create([
                'accountable_id'   => $receipt->accountable_id,
                'accountable_type' => $receipt->accountable_type,
                'description'      => 'سند صرف #' . $receipt->id,
                'debit'            => $receipt->amount,
                'credit'           => 0,
                'reference_id'     => $receipt->id,
                'reference_type'   => 'App\\Models\\Receipt'
            ]);

            DB::commit();

            return redirect()
                ->route('receipts_out.index')
                ->with('success', 'تم إنشاء سند الصرف بنجاح');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء السند: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /** عرض سند */
    public function show($id)
    {
        $receipt = Receipt::with('accountable', 'cashBox', 'user')->findOrFail($id);
        return view('receipts_out.show', compact('receipt'));
    }

    /** صفحة التعديل */
    public function edit($id)
    {
        $receipt = Receipt::findOrFail($id);

        $customers = Customer::all();
        $suppliers = Supplier::all();
        $cashBoxes = CashBox::all();

        return view('receipts_out.edit', compact('receipt', 'customers', 'suppliers', 'cashBoxes'));
    }

    /** تعديل السند */
    public function update(Request $request, $id)
    {
        $request->validate([
            'accountable_id'  => 'required|integer',
            'accountable_type'=> 'required|string',
            'amount'          => 'required|numeric',
            'cash_box_id'     => 'required|integer',
            'description'     => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            $receipt = Receipt::findOrFail($id);

            $receipt->update([
                'accountable_id'  => $request->accountable_id,
                'accountable_type'=> $request->accountable_type,
                'amount'          => $request->amount,
                'cash_box_id'     => $request->cash_box_id,
                'description'     => $request->description,
            ]);

            // حذف القيود القديمة
            LedgerEntry::where('reference_id', $receipt->id)
                ->where('reference_type', 'App\\Models\\Receipt')
                ->delete();

            // إنشاء قيود جديدة

            // الصندوق دائن
            LedgerEntry::create([
                'accountable_id'   => $receipt->cash_box_id,
                'accountable_type' => 'App\\Models\\CashBox',
                'description'      => 'تعديل سند صرف #' . $receipt->id,
                'debit'            => 0,
                'credit'           => $receipt->amount,
                'reference_id'     => $receipt->id,
                'reference_type'   => 'App\\Models\\Receipt'
            ]);

            // الحساب مدين
            LedgerEntry::create([
                'accountable_id'   => $receipt->accountable_id,
                'accountable_type' => $receipt->accountable_type,
                'description'      => 'تعديل سند صرف #' . $receipt->id,
                'debit'            => $receipt->amount,
                'credit'           => 0,
                'reference_id'     => $receipt->id,
                'reference_type'   => 'App\\Models\\Receipt'
            ]);

            DB::commit();

            return redirect()
                ->route('receipts_out.index')
                ->with('success', 'تم تعديل سند الصرف بنجاح');

        } catch (\Exception $e) {

            DB::rollRollBack();

            return back()
                ->withErrors(['error' => 'حدث خطأ أثناء التعديل: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /** حذف السند */
    public function destroy($id)
    {
        $receipt = Receipt::findOrFail($id);

        LedgerEntry::where('reference_id', $receipt->id)
            ->where('reference_type', 'App\\Models\\Receipt')
            ->delete();

        $receipt->delete();

        return back()->with('success', 'تم حذف سند الصرف بنجاح');
    }
}
