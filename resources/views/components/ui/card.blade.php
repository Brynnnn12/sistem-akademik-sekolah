@props([
    'header' => null,
    'footer' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200']) }}>
    @if ($header)
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                {{ $header }}
            </div>
        </div>
    @endif

    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $footer }}
        </div>
    @endif
</div>
