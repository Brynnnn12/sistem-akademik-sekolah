@props([
    'title' => 'Modal',
    'width' => 600,
    'showCloseButton' => true,
    'showCancelButton' => false,
    'showConfirmButton' => true,
    'confirmText' => 'OK',
    'cancelText' => 'Cancel',
    'allowOutsideClick' => true,
    'functionName' => 'showSweetModal',
])

<script>
    function {{ $functionName }}() {
        Swal.fire({
            title: '{{ $title }}',
            html: `{!! str_replace(["\n", "\r"], '', $slot) !!}`,
            width: {{ $width }},
            showCloseButton: {{ $showCloseButton ? 'true' : 'false' }},
            showCancelButton: {{ $showCancelButton ? 'true' : 'false' }},
            showConfirmButton: {{ $showConfirmButton ? 'true' : 'false' }},
            confirmButtonText: '{{ $confirmText }}',
            cancelButtonText: '{{ $cancelText }}',
            allowOutsideClick: {{ $allowOutsideClick ? 'true' : 'false' }},
            customClass: {
                container: 'sweet-modal-container',
                popup: 'sweet-modal-popup',
                header: 'sweet-modal-header',
                title: 'sweet-modal-title',
                content: 'sweet-modal-content'
            }
        });
    }
</script>

<style>
    .sweet-modal-popup {
        font-family: inherit !important;
    }

    .sweet-modal-content {
        text-align: left !important;
    }
</style>
