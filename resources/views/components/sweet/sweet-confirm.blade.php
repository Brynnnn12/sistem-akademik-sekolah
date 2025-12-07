@props([
    'title' => 'Are you sure?',
    'text' => 'You won\'t be able to revert this!',
    'confirmText' => 'Yes, do it!',
    'cancelText' => 'Cancel',
    'confirmColor' => '#dc2626',
    'cancelColor' => '#6b7280',
    'icon' => 'warning',
    'action' => '',
    'method' => 'POST',
    'params' => [],
])

<script>
    function sweetConfirm{{ $params['id'] ?? 'Action' }}() {
        Swal.fire({
            title: '{{ $title }}',
            text: '{{ $text }}',
            icon: '{{ $icon }}',
            showCancelButton: true,
            confirmButtonColor: '{{ $confirmColor }}',
            cancelButtonColor: '{{ $cancelColor }}',
            confirmButtonText: '{{ $confirmText }}',
            cancelButtonText: '{{ $cancelText }}',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @if ($action)
                    @if ($method === 'GET')
                        window.location.href = '{{ $action }}';
                    @else
                        // Create and submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ $action }}';

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        @if ($method !== 'POST')
                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = '{{ $method }}';
                            form.appendChild(methodField);
                        @endif

                        @if (!empty($params))
                            @foreach ($params as $key => $value)
                                @if ($key !== 'id')
                                    const param{{ ucfirst($key) }} = document.createElement('input');
                                    param{{ ucfirst($key) }}.type = 'hidden';
                                    param{{ ucfirst($key) }}.name = '{{ $key }}';
                                    param{{ ucfirst($key) }}.value = '{{ $value }}';
                                    form.appendChild(param{{ ucfirst($key) }});
                                @endif
                            @endforeach
                        @endif

                        document.body.appendChild(form);
                        form.submit();
                    @endif
                @else
                    {{ $slot }}
                @endif
            }
        });
    }
</script>
