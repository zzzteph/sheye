@if ($paginator->hasPages())
	
<nav class="pagination" role="navigation" aria-label="pagination">
 @if ($paginator->onFirstPage())
  <a class="pagination-previous" href="#" rel="prev" aria-label="@lang('pagination.previous')" disabled>@lang('pagination.previous')</a>
  @else
  <a class="pagination-previous" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">@lang('pagination.previous')</a>
  @endif
  
  @if ($paginator->hasMorePages())
  <a class="pagination-next" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">@lang('pagination.next')</a>
   @else
   <a class="pagination-next" href="#" disabled rel="next" aria-label="@lang('pagination.next')">@lang('pagination.next')</a>
   @endif
  <ul class="pagination-list">
 
 
 
 
 
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
					
				
                        @if ($page == $paginator->currentPage())
                            <li> <a class="pagination-link has-background-black has-text-white-bis" class="active" aria-current="page"><span>{{ $page }}</span></a></li>
                        @else
                            <li> <a class="pagination-link" class="active" aria-current="page"  href="{{ $url }}"><span>{{ $page }}</span></a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
  
  
  
  

  </ul>
</nav>
	
	
	
@endif
