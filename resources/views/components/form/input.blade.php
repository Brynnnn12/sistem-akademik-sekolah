@props([
    'type' => 'text',
    'name',
    'label' => null,
    'icon' => null,
    'error' => null,
    'help' => null,
    'required' => false,
])

@php
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?: $errors->first($name);
@endphp

<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            @if ($icon)
                <i class="{{ $icon }} mr-1.5 text-gray-400 text-xs"></i>
            @endif
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if ($icon && !$label)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-gray-400 text-sm"></i>
            </div>
        @endif

        @if ($type === 'textarea')
            <textarea name="{{ $name }}" id="{{ $name }}"
                {{ $attributes->merge([
                    'class' =>
                        'block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors' .
                        ($hasError ? ' border-red-500 focus:border-red-500 focus:ring-red-500' : '') .
                        ($icon && !$label ? ' pl-10' : ''),
                ]) }}>{{ old($name) }}</textarea>
        @elseif($type === 'select')
            <select name="{{ $name }}" id="{{ $name }}"
                {{ $attributes->merge([
                    'class' =>
                        'block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors' .
                        ($hasError ? ' border-red-500 focus:border-red-500 focus:ring-red-500' : '') .
                        ($icon && !$label ? ' pl-10' : ''),
                ]) }}>
                {{ $slot }}
            </select>
        @else
            <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
                value="{{ old($name) }}"
                {{ $attributes->merge([
                    'class' =>
                        'block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors' .
                        ($hasError ? ' border-red-500 focus:border-red-500 focus:ring-red-500' : '') .
                        ($icon && !$label ? ' pl-10' : ''),
                ]) }} />
        @endif
    </div>

    @if ($hasError)
        <p class="mt-1 text-xs text-red-600 flex items-center">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ $errorMessage }}
        </p>
    @endif

    @if ($help && !$hasError)
        <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    @endif
</div>
