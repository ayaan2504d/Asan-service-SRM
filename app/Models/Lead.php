<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_id',
        'date',
        'name',
        'contact',
        'whatsapp_number',
        'platform',
        'campaign_name',
        'response',
        'location',
        'type_of_site',
        'type_of_service',
        'size_of_area',
        'num_items',
        'booking_date',
        'service_date',
        'appointment_time',
        'estimated_amount',
        'amount_received',
    ];

    protected $casts = [
        'date'             => 'date',
        'booking_date'     => 'date',
        'service_date'     => 'date',
        'estimated_amount' => 'decimal:2',
        'amount_received'  => 'decimal:2',
    ];

    public static function generateLeadId(): string
    {
        $last = self::withTrashed()->orderByDesc('id')->first();
        $nextNumber = $last ? ((int) substr($last->lead_id, 3)) + 1 : 1;
        return 'LD-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
