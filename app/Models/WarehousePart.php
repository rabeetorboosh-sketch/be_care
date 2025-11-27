<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehousePart extends Model
{
    protected $table = 'warehouse_part';

    protected $fillable = [
        'warehouse_id',
        'part_id',
        'quantity',
        'reference_id',
        'reference_type',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
