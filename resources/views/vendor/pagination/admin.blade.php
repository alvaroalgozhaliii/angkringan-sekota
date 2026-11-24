@if ($paginator->hasPages())
    <nav class="pagination-bar" role="navigation" aria-label="Navigasi halaman">
        <div class="pagination-info">
            Menampilkan <strong>{{ $paginator->firstItem() ?? 0 }}</strong>–<strong>{{ $paginator->lastItem() ?? 0 }}</strong> dari <strong>{{ $paginator->total() }}</strong> hasil
        </div>

        <div class="pagination-pages">
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled" aria-disabled="true">Sebelumnya</span>
            @else
                <a class="pagination-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-ellipsis">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-btn active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-btn" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="pagination-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>
            @else
                <span class="pagination-btn disabled" aria-disabled="true">Berikutnya</span>
            @endif
        </div>
    </nav>
@endif
