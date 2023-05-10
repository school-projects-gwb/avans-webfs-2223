<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'name',
        'condition_text'
    ];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class);
    }
}
