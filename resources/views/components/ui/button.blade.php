@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
])

@php
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white border-transparent',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 text-white border-transparent',
        'success' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white border-transparent',
        'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white border-transparent',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 text-white border-transparent',
        'info' => 'bg-cyan-600 hover:bg-cyan-700 focus:ring-cyan-500 text-white border-transparent',
        'light' => 'bg-gray-100 hover:bg-gray-200 focus:ring-gray-500 text-gray-900 border-gray-300',
        'outline' => 'bg-transparent hover:bg-gray-50 focus:ring-gray-500 text-gray-700 border-gray-300',
        'ghost' => 'bg-transparent hover:bg-gray-100 focus:ring-gray-500 text-gray-700 border-transparent',
    ];

    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base',
        'xl' => 'px-6 py-3 text-base',
    ];

    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<button type="{{ $type }}"
    {{ $attributes->merge([
        'class' => "inline-flex items-center justify-center border font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105 active:scale-95 {$variantClass} {$sizeClass}",
    ]) }}
    @if ($loading) disabled @endif>
    @if ($loading)
        <i class="fas fa-spinner fa-spin {{ $slot->isEmpty() ? '' : 'mr-2' }} text-sm"></i>
    @elseif($icon && $iconPosition === 'left')
        <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }} text-sm"></i>
    @endif

    {{ $slot }}

    @if ($icon && $iconPosition === 'right' && !$loading)
        <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'ml-2' }} text-sm"></i>
    @endif
</button>
