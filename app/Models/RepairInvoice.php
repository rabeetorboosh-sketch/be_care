<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairInvoice extends Model
{
    protected $fillable = [
        'device_id', 'device_type', 'customer_id', 'status',
        'service_fee', 'total_parts_price',
        'total_amount', 'payment_status',
        'paid_amount', 'remaining_amount'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function parts()
    {
        return $this->hasMany(RepairInvoicePart::class);
    }
    public function type()
    {
        return $this->belongsTo(DeviceType::class, 'device_type');
    }
    public function ledgerentries()
    {
        return $this->morphMany(LedgerEntry::class, 'reference');
    }

}
