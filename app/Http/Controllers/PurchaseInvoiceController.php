<?php

namespace App\Http\Controllers;

use App\Models\LedgerEntry;
use App\Models\Part;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoicePart;
use App\Models\WarehousePart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceController extends Controller
{
    // عرض جميع الفواتير
    public function index()
    {
        $invoices = PurchaseInvoice::with('supplier', 'warehouse')->latest()->paginate(20);
        return view('purchase_invoices.index', compact('invoices'));
    }

    // صفحة إنشاء فاتورة جديدة
    public function create()
    {
        $suppliers = Supplier::all();
        $warehouses = Warehouse::all();
        $parts = Part::all();

        return view('purchase_invoices.create', compact('suppliers', 'warehouses', 'parts'));
    }

    // حفظ الفاتورة
    public function store(Request $request)
    {

        $request->validate([
            'supplier_id' => 'required',
            'warehouse_id' => 'required|exists:warehouses,id',
            'invoice_date' => 'required',
            'payment_type' => 'required',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'remaining_amount' => 'required|numeric',
            'parts' => 'array'
        ]);

        try {
            DB::transaction(function () use ($request) {

                $invoice = PurchaseInvoice::create([



                    'supplier_id' => $request->supplier_id,
                    'warehouse_id' => $request->warehouse_id,
                    'invoice_date' => $request->invoice_date,
                    'total_parts_price' => $request->total_parts_price,
                    'total_amount' => $request->total_amount,
                    'payment_status' => $request->payment_type,
                    'paid_amount' => $request->paid_amount,

                ]);

                // إنشاء الأجزاء إذا وجدت
                if ($request->has('parts')) {
                    $invoice->parts()->createMany($request->parts);
                }

                $supplierName = $invoice->supplier?->name ?? '  مورد نقدي';

                // إنشاء قيد للمورد إذا كان هناك مبلغ متبقي وآجل
                if ($request->payment_type == 'credit' && $request->supplier_id >0 && $invoice->remaining_amount > 0) {
                    LedgerEntry::create([
                        'accountable_id' => $invoice->supplier_id,
                        'accountable_type' => 'App\\Models\\Supplier',
                        'description' => "فاتورة شراء من {$supplierName}",
                        'debit' => $invoice->remaining_amount,
                        'credit' => 0,
                        'reference_id' => $invoice->id,
                        'reference_type' => PurchaseInvoice::class,
                    ]);
                }

                // إنشاء قيد للصندوق إذا كان هناك دفعة نقدية
                if ($request->paid_amount > 0 && $request->cash_box_id) {
                    LedgerEntry::create([
                        'accountable_id' => $request->cash_box_id,
                        'accountable_type' => 'App\\Models\\CashBox',
                        'description' => "فاتورة شراء من {$supplierName}",
                        'debit' => $invoice->paid_amount,
                        'credit' => 0,
                        'reference_id' => $invoice->id,
                        'reference_type' => PurchaseInvoice::class,
                    ]);
                }
            });

            return redirect()->route('purchase_invoices.index')
                ->with('success', 'تم إنشاء فاتورة الشراء بنجاح');

        } catch (Exception $e) {

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الفاتورة: ' . $e->getMessage())
                ->withInput();
        }
    }


    // صفحة تعديل الفاتورة
    public function edit(PurchaseInvoice $purchase_invoice)
    {
        $suppliers = Supplier::all();
        $warehouses = Warehouse::all();
        $parts = Part::all();

        $purchase_invoice->load('parts');

        return view('purchase_invoices.edit', [
            'invoice' => $purchase_invoice,
            'suppliers' => $suppliers,
            'warehouses' => $warehouses,
            'parts' => $parts,
        ]);
    }

    public function update(Request $request, $id)
    {
        $invoice = PurchaseInvoice::findOrFail($id);

        // التحقق من المدخلات
        $request->validate([
            'supplier_id' => 'required|integer',
            'warehouse_id' => 'required|integer',
            'invoice_date' => 'required|date',
            'payment_type' => 'required|in:cash,credit',

            'parts.*.part_id' => 'required|exists:parts,id',
            'parts.*.quantity' => 'required|numeric|min:1',
            'parts.*.purchase_price' => 'required|numeric|min:0',
        ]);

        // منع المورد 0 من الدفع الآجل
        if ($request->supplier_id == 0 && $request->payment_type == 'credit') {
            return back()->withErrors(['payment_type' => 'لا يمكن أن يكون الدفع آجل اذا كان المورد بقيمة 0']);
        }

        // حذف مخزون الفاتورة السابقة
        foreach ($invoice->parts as $oldItem) {
            $pivot = WarehousePart::where('warehouse_id', $invoice->warehouse_id)
                ->where('part_id', $oldItem->part_id)
                ->first();

            if ($pivot) {
                $pivot->quantity -= $oldItem->quantity;
                if ($pivot->quantity < 0) $pivot->quantity = 0;
                $pivot->save();
            }
        }

        // تحديث بيانات الفاتورة الأساسية
        $invoice->update([
            'supplier_id' => $request->supplier_id,
            'warehouse_id' => $request->warehouse_id,
            'invoice_date' => $request->invoice_date,
            'payment_status' => $request->payment_type,
            'paid_amount' => $request->paid_amount ?? 0,
        ]);

        // حذف الأجزاء القديمة
        $invoice->parts()->delete();

        $totalAmount = 0;

        // إدخال الأجزاء الجديدة
        foreach ($request->parts as $item) {
            $total = $item['quantity'] * $item['purchase_price'];
            $totalAmount += $total;

            $invoice->parts()->create([
                'part_id' => $item['part_id'],
                'quantity' => $item['quantity'],
                'purchase_price' => $item['purchase_price'],
                'total_price' => $total,
            ]);


            WarehousePart::updateOrCreate(
                [
                    'warehouse_id' => $invoice->warehouse_id,
                    'part_id' => $item['part_id']
                ],
                [
                    'quantity' => DB::raw("quantity + {$item['quantity']}")
                ]
            );
        }

        // تحديث الإجمالي النهائي
        $invoice->total_amount = $totalAmount;

        $invoice->save();

        // حذف القيود المحاسبية القديمة
        $invoice->ledgerEntries()->delete();

        // إنشاء قيد جديد
        $invoice->ledgerEntries()->create([
            'accountable_id' => $invoice->supplier_id,
            'accountable_type' => $invoice->supplier_id == 0 ? 'cash_box' : 'supplier',
            'description' => 'تحديث فاتورة مشتريات رقم ' . $invoice->id,
            'debit'  => $invoice->total_amount,
            'credit' => $invoice->paid_amount,
        ]);

        return redirect()->route('purchase_invoices.index')->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    // عرض الفاتورة
    public function show(PurchaseInvoice $purchase_invoice)
    {
        $purchase_invoice->load('supplier', 'warehouse', 'parts.part');
        return view('purchase_invoices.show', ['invoice' => $purchase_invoice]);
    }

    // حذف الفاتورة
    public function destroy(PurchaseInvoice $purchase_invoice)
    {
        $purchase_invoice->delete();
        return redirect()->route('purchase_invoices.index')->with('success', 'تم حذف الفاتورة بنجاح.');
    }
}
