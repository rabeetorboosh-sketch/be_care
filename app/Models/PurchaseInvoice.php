<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'supplier_id',
        'warehouse_id',
        'invoice_date',
        'total_amount',
        'payment_status',
        'payment_status',
        'cash_box_id',
        'paid_amount'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function parts()
    {
        return $this->hasMany(PurchaseInvoicePart::class);
    }
    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }
    public function ledgerEntries()
    {
        return $this->morphMany(LedgerEntry::class, 'reference');
    }
    public function cashBox()
    {
        return $this->belongsTo(CashBox::class,'cash_box_id');
    }
}
