<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'national_id', 'is_active'];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function invoices()
    {
        return $this->hasMany(RepairInvoice::class);
    }

    public function ledger()
    {
        return $this->morphMany(LedgerEntry::class, 'accountable');
    }
}
