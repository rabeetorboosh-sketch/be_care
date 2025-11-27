<?php

namespace App\Http\Controllers;

use App\Models\CashBox;
use App\Models\DeviceType;
use App\Models\LedgerEntry;
use App\Models\RepairInvoice;
use App\Models\Device;
use App\Models\Customer;
use App\Models\Part;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepairInvoiceController extends Controller
{
    public function index()
    {
        $invoices = RepairInvoice::with(['customer', 'device'])->latest()->paginate(20);
        return view('repair_invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', 1)->get();
        $devices = Device::all();
        $parts = Part::where('is_active', 1)->get();
        $device_types = DeviceType::all();
        $boxes = CashBox::all();

        return view('repair_invoices.create', compact('customers', 'devices', 'parts','device_types','boxes'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'cash_box_id' => 'nullable',
            'device_id' => 'nullable|exists:devices,id',
            'device_type' => 'nullable|exists:device_types,id',
            'status' => 'nullable',
            'service_fee' => 'required|numeric',
            'total_parts_price' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'remaining_amount' => 'required|numeric',
            'parts' => 'array'
        ]);
        try {
        DB::transaction(function () use ($request) {

            $invoice = RepairInvoice::create([
                'customer_id' => $request->customer_id,
                'device_id' => $request->device_id,
                'device_type' => $request->device_type,
                'date' => $request->invoice_date ?? now(),
                'status' => $request->status,
                'service_fee' => $request->service_fee,
                'total_parts_price' => $request->total_parts_price,
                'total_amount' => $request->total_amount,
                'payment_status' => $request->payment_type ?? 'cash',
                'paid_amount' => $request->paid_amount,
                'remaining_amount' => $request->remaining_amount,
            ]);

            // إنشاء الأجزاء إذا وجدت
            if ($request->has('parts')) {
                $invoice->parts()->createMany($request->parts);
            }

            $customerName = $invoice->customer?->name ?? 'عميل نقدي';

            // إنشاء قيد للعميل إذا كان هناك مبلغ متبقي
            if ($request->status != 'cash' && $request->customer_id > 0 && $invoice->remaining_amount > 0) {
                LedgerEntry::create([
                    'accountable_id' => $invoice->customer_id,
                    'accountable_type' => 'App\\Models\\Customer',
                    'description' => "فاتورة صيانة ل {$customerName}",
                    'debit' => $invoice->remaining_amount,
                    'credit' => 0,
                    'reference_id' => $invoice->id,
                    'reference_type' => RepairInvoice::class,
                ]);
            }

            // إنشاء قيد للصندوق إذا كان هناك دفعة نقدية
            if ($request->paid_amount > 0 && $request->cash_box_id) {
                LedgerEntry::create([
                    'accountable_id' => $request->cash_box_id,
                    'accountable_type' => 'App\\Models\\CashBox',
                    'description' => "فاتورة صيانة ل {$customerName}",
                    'debit' => $invoice->paid_amount,
                    'credit' => 0,
                    'reference_id' => $invoice->id,
                    'reference_type' => RepairInvoice::class,
                ]);
            }
        });

            return redirect()->route('repair_invoices.index')
                ->with('success', 'تم إنشاء الفاتورة بنجاح');

        } catch (Exception $e) {

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الفاتورة: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function show(RepairInvoice $repairInvoice)
    {
        $invoice = $repairInvoice->load(['customer', 'device', 'parts.part']);
        return view('repair_invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = RepairInvoice::with('parts')->findOrFail($id);

        $customers = Customer::where('is_active', 1)->get();
        $devices = Device::all();
        $parts = Part::where('is_active', 1)->get();
        $device_types = DeviceType::all();
        $boxes = CashBox::all();

        return view('repair_invoices.edit', compact(
            'invoice', 'customers', 'devices', 'parts', 'device_types', 'boxes'
        ));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required',
            'cash_box_id' => 'nullable',
            'device_id' => 'nullable|exists:devices,id',
            'device_type' => 'nullable|exists:device_types,id',
            'status' => 'nullable',
            'service_fee' => 'required|numeric',
            'total_parts_price' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'remaining_amount' => 'required|numeric',
            'parts' => 'array'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                $invoice = RepairInvoice::findOrFail($id);

                // تحديث بيانات الفاتورة
                $invoice->update([
                    'customer_id' => $request->customer_id,
                    'device_id' => $request->device_id,
                    'device_type' => $request->device_type,
                    'date' => $request->invoice_date ?? now(),
                    'status' => $request->status,
                    'service_fee' => $request->service_fee,
                    'total_parts_price' => $request->total_parts_price,
                    'total_amount' => $request->total_amount,
                    'payment_status' => $request->payment_type ?? 'cash',
                    'paid_amount' => $request->paid_amount,
                    'remaining_amount' => $request->remaining_amount,
                ]);

                // حذف الأجزاء القديمة
                $invoice->parts()->delete();

                // إعادة إدخال الأجزاء
                if ($request->has('parts')) {
                    $invoice->parts()->createMany($request->parts);
                }

                $customerName = $invoice->customer?->name ?? 'عميل نقدي';

                // حذف القيود القديمة
                LedgerEntry::where('reference_id', $invoice->id)
                    ->where('reference_type', RepairInvoice::class)
                    ->delete();

                // قيد العميل (المتبقي)
                if ($invoice->remaining_amount > 0 && $invoice->customer_id > 0) {
                    LedgerEntry::create([
                        'accountable_id' => $invoice->customer_id,
                        'accountable_type' => 'App\\Models\\Customer',
                        'description' => "تعديل فاتورة صيانة لـ {$customerName}",
                        'debit' => $invoice->remaining_amount,
                        'credit' => 0,
                        'reference_id' => $invoice->id,
                        'reference_type' => RepairInvoice::class,
                    ]);
                }

                // قيد الصندوق (المدفوع)
                if ($invoice->paid_amount > 0 && $request->cash_box_id) {
                    LedgerEntry::create([
                        'accountable_id' => $request->cash_box_id,
                        'accountable_type' => 'App\\Models\\CashBox',
                        'description' => "تعديل فاتورة صيانة لـ {$customerName}",
                        'debit' => $invoice->paid_amount,
                        'credit' => 0,
                        'reference_id' => $invoice->id,
                        'reference_type' => RepairInvoice::class,
                    ]);
                }
            });

            return redirect()->route('repair_invoices.index')
                ->with('success', 'تم تحديث الفاتورة بنجاح');

        } catch (Exception $e) {

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تعديل الفاتورة: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(RepairInvoice $repairInvoice)
    {
        $repairInvoice->parts()->delete();
        $repairInvoice->ledgerentries()->delete();
        $repairInvoice->delete();

        return redirect()->route('repair_invoices.index')->with('success', 'تم حذف الفاتورة');
    }
}
