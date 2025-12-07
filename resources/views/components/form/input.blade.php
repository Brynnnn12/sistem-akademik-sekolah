@props(['name', 'placeholder' => '', 'value' => '', 'required' => false])

<input type="text" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}"
    value="{{ old($name, $value) }}" {{ $required ? 'required' : '' }}
    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors" />
