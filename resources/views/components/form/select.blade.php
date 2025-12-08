@props(['name', 'required' => false, 'placeholder' => 'Pilih...', 'searchable' => true])

@if ($searchable)
    {{-- Tom Select Implementation --}}
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
        <style>
            .ts-control {
                border-radius: 0.5rem;
                padding: 0.5rem 0.75rem;
                border-color: #d1d5db;
                font-size: 0.875rem;
            }

            .ts-control:focus {
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
                border-color: #3b82f6;
            }

            .ts-dropdown {
                z-index: 60;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectEl = document.getElementById('{{ $name }}');
                if (selectEl && !selectEl.tomselect) {
                    new TomSelect(selectEl, {
                        create: false,
                        sortField: {
                            field: "text",
                            direction: "asc"
                        },
                        placeholder: "{{ $placeholder }}",
                        plugins: ['clear_button'],
                        dropdownParent: 'body'
                    });
                }
            });
        </script>
    @endpush

    <select name="{{ $name }}" id="{{ $name }}" {{ $required ? 'required' : '' }}
        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors"
        autocomplete="off">
        {{ $slot }}
    </select>
@else
    {{-- Regular Select Implementation --}}
    <select name="{{ $name }}" id="{{ $name }}" {{ $required ? 'required' : '' }}
        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">
        {{ $slot }}
    </select>
@endif
