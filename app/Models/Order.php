<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'is_takeaway'
    ];

    public function orderLines(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderLine::class);
    }
}
