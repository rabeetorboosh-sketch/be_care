<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'company',
        'version',
        'specs',
        'type',
        'status',
        'notes',
    ];

    public function units()
    {
        return $this->hasMany(ItemUnit::class);
    }
}
