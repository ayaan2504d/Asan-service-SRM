<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model
{
    protected $fillable = [
        'solar_client_id',
        'due_date',
        'status',
        'source',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function solarClient(): BelongsTo
    {
        return $this->belongsTo(SolarClient::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
