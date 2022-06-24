<style>
    .page_item.active{
        
    }
    .page-item .page-link{
        padding:0.3em 1.5em 0.3em 1.5em;
    }
    .page-item.disabled .page-link{
        background-color:rgb(250, 250, 250);
    }
    .pagination > li > a{
        border-color:#eee;
        margin:0;
    }
    @media only screen and (max-width: 600px) {
        .page-item .page-link{
            width:1em;
        }
        .page-item span.page-link.
        .page-item.disabled .page-link, 
        .page-item.active .page-link,
        {
        }
        .pagination a.page-link.page-first,
        .pagination a.page-link.page-last{
            display:none;
        }
    }
</style>

@php
    $cpage = $paginator->currentPage();
    $start_page = 1;
    $max_page = ($paginator->lastPage() > 5) ? 5 : $paginator->lastPage();
@endphp

@if($cpage > 3)
    @php 
        $max_page = $cpage + 3;
        $start_page = $cpage - 3;
    @endphp     
@endif
@if($cpage >= 4)
    @php 
        $max_page = $cpage + 2;
        $start_page = $cpage - 2;
    @endphp     
@endif
@if($paginator->currentPage() == $paginator->lastPage() - 1 &&  $paginator->lastPage() > 4)
    @php 
        $max_page = $paginator->lastPage();
        $start_page =  $max_page - 4;
    @endphp    
@endif
@if($paginator->currentPage() == $paginator->lastPage() &&   $paginator->lastPage() > 4)
    @php 
        $max_page = $paginator->lastPage();
        $start_page =  $max_page - 4;
    @endphp    
@endif
@if($paginator->currentPage() == $paginator->lastPage())
    @php 
        $max_page = $paginator->lastPage();
    @endphp    
@endif

<div id="custom-pagination-div" class="row">
    <div class="col-md-5 mb-2">
        <div class="pull-left">
            <p class="text-muted">
                <label>
                    page {{ $cpage }} / <span id="total-page">{{ $paginator->lastPage()  }}</span>
                    
                </label> -  
                <label>
                    {{ $paginator->perPage()  }}
                </label>
                <label> records per page | </label>
                <label>Total: {{ $paginator->total()  }} records</label>
            </p>
        </div>
    </div>
    @if ($paginator->hasPages())
    <div class="col-md-7">
        <nav class="pull-right">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span class="page-link" aria-hidden="true">&lsaquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span class="page-link" aria-hidden="true">&rsaquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
</div>
<script>
 $('html, body').animate({
    scrollTop: $("#custom-pagination-div").offset().top - 500
}, 10);
</script>






