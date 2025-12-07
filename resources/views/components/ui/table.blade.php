@props([
    'headers' => [],
    'striped' => true,
    'hover' => true,
    'responsive' => true,
])

<div class="{{ $responsive ? 'overflow-x-auto' : '' }}">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200']) }}>
        @if (!empty($headers))
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif


        <tbody class="bg-white divide-y divide-gray-200 {{ $striped ? '' : 'divide-y-0' }}">
            {{ $slot }}
        </tbody>

        @isset($footer)
            <tfoot class="bg-gray-50">
                {{ $footer }}
            </tfoot>
        @endisset
    </table>
</div>

<style>
    @if ($hover)
        tbody tr:hover {
            background-color: #f9fafb;
        }
    @endif

    @if ($striped)
        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
    @endif
</style>
