<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SolarClient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'contact',
        'whatsapp_number',
        'address',
        'area',
        'num_plates',
        'package',
        'amount',
        'payment_mode',
        'received_by',
        'first_visit_date',
        'second_visit_date',
        'third_visit_date',
        'third_visit_notes',
        'fourth_visit_date',
        'fourth_visit_notes',
        'second_amount',
        'second_payment_mode',
        'second_received_by',
        'notes',
        'reminder_date',
        'reminder_sent',
    ];

    protected $casts = [
        'first_visit_date'  => 'date',
        'second_visit_date' => 'date',
        'third_visit_date'  => 'date',
        'fourth_visit_date' => 'date',
        'reminder_date'     => 'date',
        'amount'            => 'decimal:2',
        'second_amount'     => 'decimal:2',
        'reminder_sent'     => 'boolean',
    ];

    /**
     * Total amount = first amount + second amount
     */
    public function getTotalAmountAttribute(): float
    {
        return (float)($this->amount ?? 0) + (float)($this->second_amount ?? 0);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    public function latestVisitDate(): ?\Carbon\Carbon
    {
        return collect([
            $this->first_visit_date,
            $this->second_visit_date,
            $this->third_visit_date,
            $this->fourth_visit_date,
        ])->filter()->sort()->last();
    }

    public function visitHistory(): array
    {
        return collect([
            ['label' => 'First Visit', 'date' => $this->first_visit_date, 'notes' => null],
            ['label' => 'Second Visit', 'date' => $this->second_visit_date, 'notes' => null],
            ['label' => 'Third Visit', 'date' => $this->third_visit_date, 'notes' => $this->third_visit_notes],
            ['label' => 'Fourth Visit', 'date' => $this->fourth_visit_date, 'notes' => $this->fourth_visit_notes],
        ])->filter(fn ($visit) => !empty($visit['date']))
            ->sortBy('date')
            ->values()
            ->all();
    }

    /**
     * Generate the next unique client ID: AS-00001
     */
    public static function generateClientId(): string
    {
        $last = self::withTrashed()->orderByDesc('id')->first();
        $nextNumber = $last ? ((int) substr($last->client_id, 3)) + 1 : 1;
        return 'AS-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Scope: reminders due today or overdue, not yet acknowledged
     */
    public function scopePendingReminders($query)
    {
        return $query->whereNotNull('reminder_date')
            ->where('reminder_date', '<=', now()->toDateString())
            ->where('reminder_sent', false);
    }
}
