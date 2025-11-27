<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // عرض جميع العملاء
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    // إظهار نموذج إنشاء عميل جديد
    public function create()
    {
        return view('customers.create');
    }

    // حفظ عميل جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'national_id' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'تم إنشاء العميل بنجاح');
    }

    // إظهار نموذج تعديل العميل
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // تحديث بيانات العميل
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'national_id' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'تم تحديث بيانات العميل بنجاح');
    }

    // حذف العميل
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'تم حذف العميل بنجاح');
    }
}
