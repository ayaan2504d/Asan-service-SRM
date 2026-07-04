<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolarSite extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'client_name',
        'contact',
        'address',
        'area',
        'num_plates',
        'charges',
        'visit_date',
        'visit_time',
        'team',
        'status',
        'payment_type',
        'received_by',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'charges'    => 'decimal:2',
    ];

    public static function generateSiteId(): string
    {
        $last = self::withTrashed()->orderByDesc('id')->first();
        $nextNumber = $last ? ((int) substr($last->site_id, 3)) + 1 : 1;
        return 'SS-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
