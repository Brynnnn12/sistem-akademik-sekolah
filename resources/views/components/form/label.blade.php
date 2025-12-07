@props(['for', 'required' => false])

<label for="{{ $for }}" class="block text-sm font-medium text-gray-700 mb-2">
    {{ $slot }}
    @if ($required)
        <span class="text-red-500">*</span>
    @endif
</label>
