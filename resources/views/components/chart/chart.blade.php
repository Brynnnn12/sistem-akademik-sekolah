@props([
    'type' => 'line',
    'data' => [],
    'options' => [],
    'width' => '100%',
    'height' => '400px',
    'id' => 'chart-' . uniqid(),
])

<div class="chart-container">
    <canvas id="{{ $id }}" width="{{ $width }}" height="{{ $height }}"
        {{ $attributes->merge(['class' => 'chart-canvas']) }}></canvas>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('{{ $id }}');

        if (ctx && window.Chart) {
            new Chart(ctx, {
                type: '{{ $type }}',
                data: @json($data),
                options: @json($options)
            });
        }
    });
</script>
