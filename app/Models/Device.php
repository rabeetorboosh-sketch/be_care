<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'device_type_id', 'customer_id', 'device_code', 'status', 'notes'
    ];

    public function type()
    {
        return $this->belongsTo(DeviceType::class, 'device_type_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoices()
    {
        return $this->hasMany(RepairInvoice::class);
    }
}
