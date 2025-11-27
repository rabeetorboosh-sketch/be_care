<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    protected $fillable = [
        'accountable_id', 'accountable_type',
        'description', 'debit', 'credit',
        'reference_id', 'reference_type'
    ];

    public function accountable()
    {
        return $this->morphTo();
    }
    public function reference()
    {
        return $this->morphTo();
    }
}
