@props(['paginator'])

@if ($paginator->hasPages())
    <nav
        {{ $attributes->merge(['class' => 'flex items-center justify-between bg-white px-4 py-3 sm:px-6 border-t border-gray-200']) }}>
        <!-- Mobile Pagination -->
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Next
                </a>
            @else
                <span
                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>

        <!-- Desktop Pagination -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <!-- Results Info -->
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            <!-- Pagination Links -->
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <!-- Previous Page Link -->
                    @if ($paginator->onFirstPage())
                        <span
                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:text-gray-600 focus:z-20 focus:outline-offset-0 transition-colors">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </a>
                    @endif

                    <!-- Pagination Elements -->
                    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                {{ $page }}
                            </span>
                        @elseif (
                            $page == 1 ||
                                $page == $paginator->lastPage() ||
                                ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2))
                            <a href="{{ $url }}"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 transition-colors">
                                {{ $page }}
                            </a>
                        @elseif ($page == 2 || $page == $paginator->lastPage() - 1)
                            <span
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">
                                ...
                            </span>
                        @endif
                    @endforeach

                    <!-- Next Page Link -->
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:text-gray-600 focus:z-20 focus:outline-offset-0 transition-colors">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </a>
                    @else
                        <span
                            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 cursor-not-allowed">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </nav>
@endif
