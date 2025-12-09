@props(['field'])

@if ($errors->has($field))
    <p class="mt-1 text-sm text-red-600">
        {{ $errors->first($field) }}
    </p>
@endif
