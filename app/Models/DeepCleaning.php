<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeepCleaning extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'record_id',
        'customer_name',
        'contact',
        'whatsapp_number',
        'address',
        'property_type',
        'num_rooms',
        'area_size',
        'service_charges',
        'service_date',
        'team',
        'status',
        'amount_received',
        'notes',
    ];

    protected $casts = [
        'service_date'    => 'date',
        'service_charges' => 'decimal:2',
        'amount_received' => 'decimal:2',
    ];

    public static function generateRecordId(): string
    {
        $last = self::withTrashed()->orderByDesc('id')->first();
        $nextNumber = $last ? ((int) substr($last->record_id, 3)) + 1 : 1;
        return 'DC-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
