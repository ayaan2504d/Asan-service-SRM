<?php

namespace App\Services;

use App\Models\WhatsAppSetting;
use Illuminate\Database\Eloquent\Model;

class WhatsAppService
{
    public function __construct(private ?WhatsAppSetting $settings = null)
    {
        $this->settings ??= WhatsAppSetting::current();
    }

    public function enabled(): bool
    {
        return (bool) $this->settings->enabled;
    }

    public function link(?string $number, string $message = ''): ?string
    {
        if (!$this->enabled()) {
            return null;
        }

        $normalized = $this->normalizeNumber($number);

        if (!$normalized) {
            return null;
        }

        return 'https://wa.me/'.$normalized.'?text='.rawurlencode($message);
    }

    public function normalizeNumber(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '00')) {
            return substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            return $this->settings->default_country_code.substr($digits, 1);
        }

        if (strlen($digits) <= 10) {
            return $this->settings->default_country_code.$digits;
        }

        return $digits;
    }

    public function render(string $templateKey, Model|array $customer, array $extra = []): string
    {
        $template = $this->settings->{$templateKey} ?? '';
        $data = array_merge($this->customerData($customer), $extra);

        foreach ($data as $key => $value) {
            $template = str_replace('{'.$key.'}', (string) $value, $template);
        }

        return $template;
    }

    private function customerData(Model|array $customer): array
    {
        $get = fn (string $key, mixed $default = '') => is_array($customer)
            ? ($customer[$key] ?? $default)
            : ($customer->{$key} ?? $default);

        return [
            'id' => $get('client_id', $get('lead_id', $get('record_id'))),
            'name' => $get('name', $get('customer_name', 'Customer')),
            'contact' => $get('contact'),
            'whatsapp_number' => $get('whatsapp_number', $get('contact')),
            'latest_visit' => method_exists($customer, 'latestVisitDate')
                ? ($customer->latestVisitDate()?->format('d M Y') ?? '')
                : '',
            'reminder_date' => $get('reminder_date')?->format('d M Y') ?? '',
            'amount' => $get('amount_received', $get('amount', '')),
        ];
    }
}
