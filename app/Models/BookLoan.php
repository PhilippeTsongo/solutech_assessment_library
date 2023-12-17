<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookLoan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'book_id', 'loan_date', 'return_date', 'extended', 'extension_date',
        'due_date', 'penalty_amount', 'penalty_status', 'status', 'added_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
