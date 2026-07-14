@if ($paginator->hasPages())
    <button
        @if ($paginator->onFirstPage()) disabled @endif
        onclick="window.location='{{ $paginator->previousPageUrl() }}'"
        class="p-1 text-secondary hover:bg-white rounded border border-transparent hover:border-outline-variant transition-all disabled:opacity-30"
    >
        <span class="material-symbols-outlined">chevron_left</span>
    </button>

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-2 text-secondary">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <button class="px-3 py-1 bg-primary text-white font-label-sm rounded">{{ $page }}</button>
                @else
                    <button onclick="window.location='{{ $url }}'" class="px-3 py-1 bg-white border border-outline-variant text-on-surface font-label-sm rounded hover:bg-surface-bright">{{ $page }}</button>
                @endif
            @endforeach
        @endif
    @endforeach

    <button
        @if (! $paginator->hasMorePages()) disabled @endif
        onclick="window.location='{{ $paginator->nextPageUrl() }}'"
        class="p-1 text-secondary hover:bg-white rounded border border-transparent hover:border-outline-variant transition-all disabled:opacity-30"
    >
        <span class="material-symbols-outlined">chevron_right</span>
    </button>
@endif