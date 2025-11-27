<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'receipt_type',
        'accountable_id', 'accountable_type',
        'amount', 'description',
        'cash_box_id', 'created_by'
    ];

    public function accountable()
    {
        return $this->morphTo();
    }

    public function cashBox()
    {
        return $this->belongsTo(CashBox::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
