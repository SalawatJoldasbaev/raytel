<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children')->select('id', 'parent_id', 'name');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
