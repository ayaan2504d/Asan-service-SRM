<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_id',
        'date',
        'name',
        'contact',
        'whatsapp_number',
        'address',
        'platform',
        'lead_source',
        'campaign_name',
        'response',
        'location',
        'type_of_site',
        'type_of_service',
        'service_type',
        'size_of_area',
        'num_items',
        'booking_date',
        'service_date',
        'appointment_time',
        'estimated_amount',
        'amount_received',
        'status',
        'num_plates',
        'quoted_amount',
        'num_sofas',
        'sofa_type',
        'house_size',
        'floors',
        'notes',
        'converted_type',
        'converted_id',
    ];

    protected $casts = [
        'date'             => 'date',
        'booking_date'     => 'date',
        'service_date'     => 'date',
        'estimated_amount' => 'decimal:2',
        'amount_received'  => 'decimal:2',
        'quoted_amount'    => 'decimal:2',
        'num_plates'       => 'integer',
        'num_sofas'        => 'integer',
    ];

    public static function generateLeadId(): string
    {
        $last = self::withTrashed()->orderByDesc('id')->first();
        $nextNumber = $last ? ((int) preg_replace('/[^0-9]/', '', $last->lead_id)) + 1 : 1;
        return 'LD-' . str_pad((string)$nextNumber, 5, '0', STR_PAD_LEFT);
    }

    protected function customerName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name,
            set: fn ($value) => ['name' => $value]
        );
    }

    protected function phoneNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->contact,
            set: fn ($value) => ['contact' => $value]
        );
    }

    public function getEffectiveServiceTypeAttribute(): string
    {
        return $this->service_type ?: ($this->type_of_service ?: 'Solar Cleaning');
    }

    public function getEffectiveLeadSourceAttribute(): string
    {
        return $this->lead_source ?: ($this->platform ?: 'Other');
    }

    public function getEffectiveStatusAttribute(): string
    {
        return $this->status ?: 'Pending';
    }

    public function getEffectiveAddressAttribute(): ?string
    {
        return $this->address ?: $this->location;
    }
}
