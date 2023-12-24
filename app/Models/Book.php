<?php

namespace App\Models;

use App\Models\BookLoan;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id', 'name', 'publisher', 'isbn', 'category_id', 'subcategory_id', 'description', 'page', 'image', 'added_by', 'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function loans()
    {
        return $this->hasMany(BookLoan::class);
    }
}
