<?php

namespace App\Http\Controllers;

use App\Models\CashBox;
use App\Models\Customer;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Part;
use App\Models\Receipt;
use App\Models\LedgerEntry;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    /** عرض كل السندات */
    public function index()
    {
        $receipts = Receipt::with('accountable', 'cashBox', 'user')->where('receipt_type','in')
            ->latest()
            ->paginate(20);

        return view('receipts.index', compact('receipts'));
    }

    /** صفحة الإنشاء */
    public function create()
    {

        $customers = Customer::where('is_active', 1)->get();
        $suppliers = Supplier::all();
        $cashBoxes = CashBox::all();

        return view('receipts.create',compact('customers','suppliers','cashBoxes'));
    }

    /** حفظ السند */
    public function store(Request $request)
    {
        $request->validate([
            'receipt_type'    => 'required',
            'accountable_id'  => 'required|integer',
            'accountable_type'=> 'required|string',
            'amount'          => 'required|numeric',
            'cash_box_id'     => 'required|integer',
            'description'     => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            // إنشاء السند
            $receipt = Receipt::create([
                'receipt_type'    => $request->receipt_type,
                'accountable_id'  => $request->accountable_id,
                'accountable_type'=> $request->accountable_type,
                'amount'          => $request->amount,
                'cash_box_id'     => $request->cash_box_id,
                'description'     => $request->description,
                'created_by'      => Auth::id(),
            ]);

            /* -------------------------------------------------------
            |   إنشاء قيود اليومية (LedgerEntry)
            |   سند قبض = الصندوق مدين + العميل/المورد دائن
            --------------------------------------------------------*/

            // الصندوق (مدين)
            LedgerEntry::create([
                'accountable_id'   => $receipt->cash_box_id,
                'accountable_type' => 'App\\Models\\CashBox',
                'description'      => 'سند قبض رقم #' . $receipt->id,
                'debit'            => $receipt->amount,
                'credit'           => 0,
                'reference_id'     => $receipt->id,
                'reference_type'   => 'App\\Models\\Receipt'
            ]);

            // العميل/المورد (دائن)
            LedgerEntry::create([
                'accountable_id'   => $receipt->accountable_id,
                'accountable_type' => $receipt->accountable_type,
                'description'      => 'سند قبض رقم #' . $receipt->id,
                'debit'            => 0,
                'credit'           => $receipt->amount,
                'reference_id'     => $receipt->id,
                'reference_type'   => 'App\\Models\\Receipt'
            ]);

            DB::commit();

            return redirect()
                ->route('receipts.index')
                ->with('success', 'تم إنشاء سند القبض بنجاح');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء السند: ' . $e->getMessage()])
                ->withInput();
        }
    }


    /** عرض سند واحد */
    public function show($id)
    {
        $receipt = Receipt::with('accountable', 'cashBox', 'user')->findOrFail($id);
        return view('receipts.show', compact('receipt'));
    }
    public function edit($id)
    {
        $receipt = Receipt::findOrFail($id);

        $customers  = Customer::all();
        $suppliers  = Supplier::all();
        $cashBoxes  = CashBox::all();

        return view('receipts.edit', compact('receipt', 'customers', 'suppliers', 'cashBoxes'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'receipt_type'    => 'required',
            'accountable_id'  => 'required|integer',
            'accountable_type'=> 'required|string',
            'amount'          => 'required|numeric',
            'cash_box_id'     => 'required|integer',
            'description'     => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            $receipt = Receipt::findOrFail($id);

            // تحديث بيانات السند
            $receipt->update([
                'receipt_type'    => $request->receipt_type,
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

            /* -------------------------------------------------------
            |   إنشاء قيود جديدة
            --------------------------------------------------------*/

            // الصندوق (مدين)
            LedgerEntry::create([
                'accountable_id'   => $receipt->cash_box_id,
                'accountable_type' => 'App\\Models\\CashBox',
                'description'      => 'تعديل سند قبض #' . $receipt->id,
                'debit'            => $receipt->amount,
                'credit'           => 0,
                'reference_id'     => $receipt->id,
                'reference_type'   => 'App\\Models\\Receipt'
            ]);

            // العميل / المورد (دائن)
            LedgerEntry::create([
                'accountable_id'   => $receipt->accountable_id,
                'accountable_type' => $receipt->accountable_type,
                'description'      => 'تعديل سند قبض #' . $receipt->id,
                'debit'            => 0,
                'credit'           => $receipt->amount,
                'reference_id'     => $receipt->id,
                'reference_type'   => 'App\\Models\\Receipt'
            ]);

            DB::commit();

            return redirect()
                ->route('receipts.index')
                ->with('success', 'تم تعديل سند القبض بنجاح');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withErrors(['error' => 'حدث خطأ أثناء التعديل: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /** حذف السند */
    public function destroy($id)
    {
        $receipt = Receipt::findOrFail($id);

        // حذف القيود المرتبطة بالسند
        LedgerEntry::where('reference_id', $receipt->id)
            ->where('reference_type', 'App\\Models\\Receipt')
            ->delete();

        $receipt->delete();

        return back()->with('success', 'تم حذف سند القبض بنجاح');
    }

    public function getAccountables($type)
    {
        $accountables = [];

        switch($type) {
            case 'App\Models\Customer':
                $accountables = Customer::select('id','name')->get();
                break;
            case 'App\Models\Supplier':
                $accountables = Supplier::select('id','name')->get();
                break;
            case 'App\Models\CashBox':
                $accountables = CashBox::select('id','name')->get();
                break;
        }

        // نعيد JSON حتى يسهل استخدامه في JS
        return response()->json($accountables);
    }
}
