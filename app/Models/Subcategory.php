<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'category_id'];
    
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
