<!-- Previous page symbol "<" Link -->

@if ($paginator->hasPages())

    <ul class="pagination float-right">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">
                        <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>

                        <span>
                            <span class="sr-only">
                                <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>

                            </span>
                </a>
            </li>
        @else
            <li class="page-item"><a href="{{ $paginator->previousPageUrl() }}" class="page-link"
                    rel="prev">
                    <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>

                </a></li>
        @endif

        {{-- Pagination Elements(represents page number such as 1,2,3) --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled">{{ $element }}</li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <a href="#" class="page-link">{{ $page }}<span
                                    class="sr-only">(current)</span></a>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page symbol ">" Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a href="{{ $paginator->nextPageUrl() }}" class="page-link"
                    rel="next">
                    <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>

                </a></li>
        @else
            <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">
                    <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>

                    </span>
                    <span class="sr-only">
                    <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>

                    </span>
                </a>
            </li>
        @endif
    </ul>
@endif
