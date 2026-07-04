<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppSetting extends Model
{
    protected $fillable = [
        'business_number',
        'default_country_code',
        'enabled',
        'solar_follow_up_template',
        'sofa_cleaning_reminder_template',
        'deep_cleaning_reminder_template',
        'payment_reminder_template',
        'thank_you_template',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public static function defaults(): array
    {
        return [
            'business_number' => null,
            'default_country_code' => '92',
            'enabled' => true,
            'solar_follow_up_template' => 'Assalam o Alaikum {name}, this is Asan Home Service. Your solar follow-up for customer ID {id} is due. Latest visit: {latest_visit}.',
            'sofa_cleaning_reminder_template' => 'Assalam o Alaikum {name}, this is Asan Home Service. We are following up about your sofa cleaning service.',
            'deep_cleaning_reminder_template' => 'Assalam o Alaikum {name}, this is Asan Home Service. We are following up about your deep cleaning service.',
            'payment_reminder_template' => 'Assalam o Alaikum {name}, this is Asan Home Service. This is a gentle payment reminder for customer ID {id}.',
            'thank_you_template' => 'Thank you {name} for choosing Asan Home Service. We appreciate your trust.',
        ];
    }

    public static function current(): self
    {
        $setting = self::query()->first();

        if ($setting) {
            return $setting;
        }

        return self::create(self::defaults());
    }
}
