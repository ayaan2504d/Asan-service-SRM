<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SofaCleaning extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'record_id',
        'customer_name',
        'contact',
        'whatsapp_number',
        'address',
        'sofa_type',
        'num_seats',
        'service_charges',
        'booking_date',
        'service_date',
        'team',
        'status',
        'amount_received',
        'notes',
    ];

    protected $casts = [
        'booking_date'    => 'date',
        'service_date'    => 'date',
        'service_charges' => 'decimal:2',
        'amount_received' => 'decimal:2',
    ];

    public static function generateRecordId(): string
    {
        $last = self::withTrashed()->orderByDesc('id')->first();
        $nextNumber = $last ? ((int) substr($last->record_id, 3)) + 1 : 1;
        return 'SF-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
