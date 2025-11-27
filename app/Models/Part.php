<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = ['name', 'purchase_price', 'selling_price', 'is_active'];

    public function invoiceParts()
    {
        return $this->hasMany(RepairInvoicePart::class);
    }
}
