<nav aria-label="Product pagination" class="mt-5">
    <ul class="pagination justify-content-center">
        {{-- Previous Page Link --}}
        <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $isValidPage($currentPage - 1) ? $getPageUrl($currentPage - 1) : '#' }}" 
               tabindex="{{ $currentPage <= 1 ? '-1' : '0' }}" 
               aria-disabled="{{ $currentPage <= 1 ? 'true' : 'false' }}">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        {{-- Page Number Links --}}
        @foreach($getPageNumbers() as $page)
            <li class="page-item {{ $page == $currentPage ? 'active' : '' }}" aria-current="{{ $page == $currentPage ? 'page' : '' }}">
                <a class="page-link {{ $page != $currentPage ? 'text-dark' : '' }}" href="{{ $getPageUrl($page) }}">
                    {{ $page }}
                </a>
            </li>
        @endforeach

        {{-- Next Page Link --}}
        <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $isValidPage($currentPage + 1) ? $getPageUrl($currentPage + 1) : '#' }}"
               tabindex="{{ $currentPage >= $totalPages ? '-1' : '0' }}" 
               aria-disabled="{{ $currentPage >= $totalPages ? 'true' : 'false' }}">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>