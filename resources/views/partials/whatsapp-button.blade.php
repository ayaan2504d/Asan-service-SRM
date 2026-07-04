@php
    $wa = $wa ?? new \App\Services\WhatsAppService();
    $number = $number ?? null;
    $message = $message ?? '';
    $label = $label ?? '';
    $href = $wa->link($number, $message);
@endphp

@if($href)
    <a href="{{ $href }}" target="_blank" rel="noopener" class="{{ $class ?? 'btn btn-sm btn-success btn-icon' }}" title="{{ $title ?? 'Open WhatsApp chat' }}">
        <i class="bi bi-whatsapp"></i>@if($label)<span class="ms-1">{{ $label }}</span>@endif
    </a>
@endif
