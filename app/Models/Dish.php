<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_number',
        'menu_addition',
        'price',
        'description',
        'name',
        'is_discount',
        'optional_dish_limit'
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
