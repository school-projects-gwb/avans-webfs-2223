<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combination extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'name',
        'comment'
    ];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}