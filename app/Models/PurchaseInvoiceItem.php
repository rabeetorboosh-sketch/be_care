<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceItem extends Model
{
    protected $fillable = [
        'purchase_invoice_id',
        'item_id',
        'unit_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    // العلاقة بالفاتورة
    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    // العلاقة بالصنف
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // العلاقة بالوحدة المختارة للصنف
    public function unit()
    {
        return $this->belongsTo(ItemUnit::class, 'unit_id');
    }

    // العلاقة مع مخزون المخزن (WarehouseItem)
    public function warehouseItems()
    {
        return $this->morphMany(WarehouseItem::class, 'reference');
    }
}
