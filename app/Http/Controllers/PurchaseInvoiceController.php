<?php

namespace App\Http\Controllers;

use App\Models\CashBox;
use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\LedgerEntry;
use App\Models\Part;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoicePart;
use App\Models\WarehouseItem;
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
        $items = Item::all();  // ← إضافة الأصناف هنا
        $cashBoxes = CashBox::all();
        $itemUnits = ItemUnit::with('unit')->get();

        return view('purchase_invoices.create', compact('suppliers', 'warehouses', 'parts', 'items', 'cashBoxes','itemUnits'));
    }
    // حفظ الفاتورة
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'warehouse_id' => 'required|exists:warehouses,id',
            'cash_box_id' => 'nullable',
            'invoice_date' => 'required',
            'payment_type' => 'required',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'remaining_amount' => 'required|numeric',
            'parts' => 'array',
            'items' => 'array',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $invoice = PurchaseInvoice::create([
                    'supplier_id' => $request->supplier_id,
                    'warehouse_id' => $request->warehouse_id,
                    'cash_box_id' => $request->cash_box_id,
                    'invoice_date' => $request->invoice_date,
                    'total_parts_price' => $request->total_parts_price ?? 0,
                    'total_amount' => $request->total_amount,
                    'payment_status' => $request->payment_type,
                    'paid_amount' => $request->paid_amount,
                ]);

                $supplierName = $invoice->supplier?->name ?? 'مورد نقدي';
                $remaining_amount = $request->total_amount - $request->paid_amount;

                // حفظ القطع إذا وجدت
                if ($request->has('parts')) {
                    foreach ($request->parts as $partData) {
                        $part = $invoice->parts()->create($partData);

                        WarehousePart::create([
                            'warehouse_id' => $request->warehouse_id,
                            'part_id' => $partData['part_id'],
                            'quantity' => $partData['quantity'],
                            'reference_id' => $invoice->id,
                            'reference_type' => PurchaseInvoice::class,
                        ]);
                    }
                }

                // حفظ الأصناف إذا وجدت
                if ($request->has('items')) {
                    foreach ($request->items as $itemData) {
                        $item = $invoice->items()->create($itemData);

                        WarehouseItem::create([
                            'warehouse_id' => $request->warehouse_id,
                            'item_id' => $itemData['item_id'],
                            'quantity' => $itemData['quantity'],
                            'reference_id' => $invoice->id,
                            'reference_type' => PurchaseInvoice::class,
                        ]);
                    }
                }

                // إنشاء قيد للمورد إذا كان هناك مبلغ متبقي وآجل
                if ($request->payment_type == 'credit' && $request->supplier_id > 0 && $remaining_amount > 0) {
                    LedgerEntry::create([
                        'accountable_id' => $invoice->supplier_id,
                        'accountable_type' => 'App\\Models\\Supplier',
                        'description' => "فاتورة شراء من {$supplierName}",
                        'debit' => $remaining_amount,
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
dd($e);
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
        $items = Item::all();  // ← إضافة الأصناف هنا
        $cashBoxes = CashBox::all();
        $itemUnits = ItemUnit::with('unit')->get();
        $parts = Part::all();

        $purchase_invoice->load('parts');

        return view('purchase_invoices.edit', [
            'invoice' => $purchase_invoice,
            'suppliers' => $suppliers,
            'warehouses' => $warehouses,
            'cashBoxes' => $cashBoxes,
            'parts' => $parts,
            'items' => $items,
            'itemUnits' => $itemUnits,
        ]);
    }

    public function update(Request $request, $id)
    {
        $invoice = PurchaseInvoice::findOrFail($id);

        $request->validate([
            'supplier_id' => 'required|integer',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'cash_box_id' => 'nullable|integer',
            'invoice_date' => 'required|date',
            'payment_type' => 'required|in:cash,credit',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'remaining_amount' => 'required|numeric',
            'parts' => 'array',
            'items' => 'array',
        ]);

        // منع المورد 0 من الدفع الآجل
        if ($request->supplier_id == 0 && $request->payment_type == 'credit') {
            return back()->withErrors(['payment_type' => 'لا يمكن أن يكون الدفع آجل إذا كان المورد نقدي']);
        }

        try {
            DB::transaction(function () use ($request, $invoice) {

                $invoice->update([
                    'supplier_id' => $request->supplier_id,
                    'warehouse_id' => $request->warehouse_id,
                    'cash_box_id' => $request->cash_box_id,
                    'invoice_date' => $request->invoice_date,
                    'payment_status' => $request->payment_type,
                    'total_parts_price' => $request->total_parts_price ?? 0,
                    'total_amount' => $request->total_amount,
                    'paid_amount' => $request->paid_amount,
                ]);

                // حذف القطع والأجهزة القديمة
                $invoice->parts()->delete();
                $invoice->items()->delete();

                WarehousePart::where('reference_id', $invoice->id)
                    ->where('reference_type', PurchaseInvoice::class)
                    ->delete();

                WarehouseItem::where('reference_id', $invoice->id)
                    ->where('reference_type', PurchaseInvoice::class)
                    ->delete();

                $remaining_amount = $request->total_amount - $request->paid_amount;
                $supplierName = $invoice->supplier?->name ?? 'مورد نقدي';

                // حفظ القطع الجديدة
                if ($request->has('parts')) {
                    foreach ($request->parts as $partData) {
                        $part = $invoice->parts()->create($partData);

                        WarehousePart::create([
                            'warehouse_id' => $request->warehouse_id,
                            'part_id' => $partData['part_id'],
                            'quantity' => $partData['quantity'],
                            'reference_id' => $invoice->id,
                            'reference_type' => PurchaseInvoice::class,
                        ]);
                    }
                }

                // حفظ الأجهزة الجديدة
                if ($request->has('items')) {
                    foreach ($request->items as $itemData) {
                        $item = $invoice->items()->create($itemData);

                        WarehouseItem::create([
                            'warehouse_id' => $request->warehouse_id,
                            'item_id' => $itemData['item_id'],
                            'quantity' => $itemData['quantity'],
                            'reference_id' => $invoice->id,
                            'reference_type' => PurchaseInvoice::class,
                        ]);
                    }
                }

                // حذف القيود القديمة للفاتورة
                LedgerEntry::where('reference_id', $invoice->id)
                    ->where('reference_type', PurchaseInvoice::class)
                    ->delete();

                // إنشاء قيد المورد إذا الدفع آجل
                if ($request->payment_type == 'credit' && $request->supplier_id > 0 && $remaining_amount > 0) {
                    LedgerEntry::create([
                        'accountable_id' => $invoice->supplier_id,
                        'accountable_type' => Supplier::class,
                        'description' => "فاتورة شراء من {$supplierName}",
                        'debit' => $remaining_amount,
                        'credit' => 0,
                        'reference_id' => $invoice->id,
                        'reference_type' => PurchaseInvoice::class,
                    ]);
                }

                // إنشاء قيد للصندوق إذا هناك دفعة نقدية
                if ($request->paid_amount > 0 && $request->cash_box_id) {
                    LedgerEntry::create([
                        'accountable_id' => $request->cash_box_id,
                        'accountable_type' => CashBox::class,
                        'description' => "فاتورة شراء من {$supplierName}",
                        'debit' => $request->paid_amount,
                        'credit' => 0,
                        'reference_id' => $invoice->id,
                        'reference_type' => PurchaseInvoice::class,
                    ]);
                }

            });

            return redirect()->route('purchase_invoices.index')
                ->with('success', 'تم تحديث الفاتورة بنجاح');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'حدث خطأ أثناء تحديث الفاتورة: ' . $e->getMessage())
                ->withInput();
        }
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
        DB::transaction(function () use ($purchase_invoice) {
            // حذف الأجزاء المرتبطة بالفاتورة
            $purchase_invoice->parts()->delete();

            // حذف سجلات المخزون المرتبطة بالفاتورة
            WarehousePart::where('reference_id', $purchase_invoice->id)
                ->where('reference_type', PurchaseInvoice::class)
                ->delete();

            // حذف القيود المحاسبية المرتبطة بالفاتورة
            $purchase_invoice->ledgerEntries()->delete();

            // حذف الفاتورة نفسها
            $purchase_invoice->delete();
        });
        return redirect()->route('purchase_invoices.index')->with('success', 'تم حذف الفاتورة بنجاح.');
    }
}
