@props(['variant' => 'info'])

@php
    $classes = match ($variant) {
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'danger', 'error' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        default => 'bg-gray-50 border-gray-200 text-gray-800',
    };
@endphp

<div class="border-l-4 p-4 {{ $classes }}" role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            @if ($variant === 'success')
                <i class="fas fa-check-circle text-green-400"></i>
            @elseif($variant === 'danger' || $variant === 'error')
                <i class="fas fa-exclamation-circle text-red-400"></i>
            @elseif($variant === 'warning')
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            @else
                <i class="fas fa-info-circle text-blue-400"></i>
            @endif
        </div>
        <div class="ml-3">
            {{ $slot }}
        </div>
    </div>
</div>
