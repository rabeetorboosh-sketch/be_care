<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashBox extends Model
{
    protected $fillable = ['name', 'opening_balance', 'is_active','is_main'];

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function ledger()
    {
        return $this->morphMany(LedgerEntry::class, 'accountable');
    }
}
