<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairInvoicePart extends Model
{
    protected $fillable = [
        'repair_invoice_id', 'part_id',
        'qty', 'unit_price', 'total_price'
    ];

    public function invoice()
    {
        return $this->belongsTo(RepairInvoice::class, 'repair_invoice_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
