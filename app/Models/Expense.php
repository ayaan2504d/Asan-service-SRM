<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expense_id',
        'date',
        'title',
        'category',
        'description',
        'amount',
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];

    public static function generateExpenseId(): string
    {
        $last = self::withTrashed()->orderByDesc('id')->first();
        $nextNumber = $last ? ((int) substr($last->expense_id, 3)) + 1 : 1;
        return 'EX-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
