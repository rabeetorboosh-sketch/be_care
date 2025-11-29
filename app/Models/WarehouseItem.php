<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseItem extends Model
{
    protected $fillable = [
        'warehouse_id',
        'item_id',
        'quantity',
        'reference_id',
        'reference_type',
    ];

    // العلاقة بالمخزن
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // العلاقة بالصنف
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // العلاقة المرجعية (مثل فاتورة شراء)
    public function reference()
    {
        return $this->morphTo();
    }
}
