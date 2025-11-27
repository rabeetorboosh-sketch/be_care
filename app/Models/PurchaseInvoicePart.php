<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoicePart extends Model
{
    protected $fillable = [
        'purchase_invoice_id',
        'part_id',
        'quantity',
        'purchase_price',
        'total_price'
    ];

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
