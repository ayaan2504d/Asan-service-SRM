<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerVisit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'contact',
        'service_type',
        'visit_number',
        'visit_date',
        'status',
        'amount',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'amount'     => 'decimal:2',
    ];

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['Scheduled', 'Pending']);
    }
}
