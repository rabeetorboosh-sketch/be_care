<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'location',
        'is_active',
        'is_main'
    ];

    // العلاقة مع قطع المخزن
    public function parts()
    {
        return $this->belongsToMany(Part::class, 'warehouse_part')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // العلاقة مع فواتير المشتريات
    public function purchaseInvoices()
    {
        return $this->hasMany(PurchaseInvoice::class);
    }
}
