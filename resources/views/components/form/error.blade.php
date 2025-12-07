@props(['name'])

@if ($errors->has($name))
    <p class="mt-1 text-xs text-red-600 flex items-center">
        <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first($name) }}
    </p>
@endif
