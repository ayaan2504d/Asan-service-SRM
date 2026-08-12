<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceReminder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'contact',
        'service_type',
        'last_service_date',
        'next_reminder_date',
        'status',
        'reminder_type',
        'notes',
    ];

    protected $casts = [
        'last_service_date' => 'date',
        'next_reminder_date' => 'date',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeDue($query)
    {
        return $query->where('status', 'Pending')
            ->whereDate('next_reminder_date', '<=', now()->toDateString());
    }
}
