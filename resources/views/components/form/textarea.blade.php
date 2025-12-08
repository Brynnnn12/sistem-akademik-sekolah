@props(['name', 'placeholder' => '', 'value' => '', 'required' => false, 'rows' => 3])

<textarea name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }} rows="{{ $rows }}"
    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">{{ old($name, $value) }}</textarea>
