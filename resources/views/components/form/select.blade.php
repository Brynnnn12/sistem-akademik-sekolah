@props(['name', 'required' => false])

<select name="{{ $name }}" id="{{ $name }}" {{ $required ? 'required' : '' }}
    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">
    {{ $slot }}
</select>
