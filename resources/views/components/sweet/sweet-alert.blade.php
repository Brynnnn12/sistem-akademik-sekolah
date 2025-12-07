@props([
    'type' => 'info',
    'title' => '',
    'text' => '',
    'confirmButton' => 'OK',
    'showOnLoad' => false,
    'timer' => null,
    'position' => 'center',
    'toast' => false,
])

@php
    $alertConfig = [
        'success' => [
            'icon' => 'success',
            'confirmButtonColor' => '#10b981',
        ],
        'error' => [
            'icon' => 'error',
            'confirmButtonColor' => '#dc2626',
        ],
        'warning' => [
            'icon' => 'warning',
            'confirmButtonColor' => '#f59e0b',
        ],
        'info' => [
            'icon' => 'info',
            'confirmButtonColor' => '#3b82f6',
        ],
    ];

    $config = $alertConfig[$type] ?? $alertConfig['info'];
@endphp

@if ($showOnLoad)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($toast)
                const Toast = Swal.mixin({
                    toast: true,
                    position: '{{ $position === 'center' ? 'top-end' : $position }}',
                    showConfirmButton: false,
                    timer: {{ $timer ?? 3000 }},
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: '{{ $config['icon'] }}',
                    title: '{{ $title }}',
                    text: '{{ $text }}'
                });
            @else
                Swal.fire({
                    @if ($title)
                        title: '{{ $title }}',
                    @endif
                    @if ($text)
                        text: '{{ $text }}',
                    @endif
                    icon: '{{ $config['icon'] }}',
                    confirmButtonColor: '{{ $config['confirmButtonColor'] }}',
                    confirmButtonText: '{{ $confirmButton }}',
                    @if ($timer)
                        timer: {{ $timer }},
                    @endif
                    @if ($timer)
                        showConfirmButton: false,
                    @endif
                });
            @endif
        });
    </script>
@endif
