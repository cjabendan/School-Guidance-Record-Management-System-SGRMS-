@props(['paginator'])

@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-links page-item disabled">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-links page-item" rel="prev">&laquo;</a>
        @endif

        {{-- Pagination Links --}}
        @foreach ($paginator->links()->elements[0] ?? [] as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="pagination-links page-item active" style="background-color:#1ea7ff;color:#fff;">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="pagination-links page-item">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-links page-item" rel="next">&raquo;</a>
        @else
            <span class="pagination-links page-item disabled">&raquo;</span>
        @endif
    </div>
@endif
