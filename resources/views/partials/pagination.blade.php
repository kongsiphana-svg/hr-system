@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between px-2 py-4">

        {{-- Results summary --}}
        <p class="text-label-sm text-on-surface-variant">
            Showing
            <span class="font-semibold text-on-surface">{{ $paginator->firstItem() }}</span>
            to
            <span class="font-semibold text-on-surface">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-semibold text-on-surface">{{ $paginator->total() }}</span>
            results
        </p>

        <div class="flex items-center gap-2">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center w-9 h-9 rounded-lg border border-outline-variant text-on-surface-variant opacity-40 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]" data-icon="chevron_left">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="flex items-center justify-center w-9 h-9 rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-high transition-colors"
                   rel="prev">
                    <span class="material-symbols-outlined text-[18px]" data-icon="chevron_left">chevron_left</span>
                </a>
            @endif

            {{-- Page Number Links --}}
            @foreach ($elements as $element)

                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="flex items-center justify-center w-9 h-9 text-on-surface-variant text-label-md">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-surface-tint text-on-primary font-bold text-label-md">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="flex items-center justify-center w-9 h-9 rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-high font-medium text-label-md transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="flex items-center justify-center w-9 h-9 rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-high transition-colors"
                   rel="next">
                    <span class="material-symbols-outlined text-[18px]" data-icon="chevron_right">chevron_right</span>
                </a>
            @else
                <span class="flex items-center justify-center w-9 h-9 rounded-lg border border-outline-variant text-on-surface-variant opacity-40 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]" data-icon="chevron_right">chevron_right</span>
                </span>
            @endif

        </div>
    </nav>
@endif