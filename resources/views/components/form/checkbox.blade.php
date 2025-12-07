@props(['name', 'label', 'description' => null, 'checked' => false])

<div class="flex items-start">
    <div class="flex items-center h-5">
        <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="1"
            {{ $checked ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
    </div>
    <div class="ml-3 text-sm">
        <label for="{{ $name }}" class="font-medium text-gray-700">{{ $label }}</label>
        @if ($description)
            <p class="text-gray-500">{{ $description }}</p>
        @endif
    </div>
</div>
