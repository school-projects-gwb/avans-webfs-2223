<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'menu_number',
        'menu_addition',
        'price',
        'is_discount',
        'option_required',
        'option_amount',
        'category_id'
    ];

    public function options()
    {
        return $this->belongsToMany(Option::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
