@props(['label', 'required' => false])

<div class="mb-4">
    <label for="{{ $attributes->get('id') ?? '' }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <div>
        {{ $slot }}
    </div>
</div>
