<nav aria-label="Product pagination" class="mt-5">
    <ul class="pagination justify-content-center">

        <li class="page-item {{ $paginator->currentPage() <= 1 ? 'disabled' : '' }}">
            <a class="page-link border-0 bg-transparent" href="{{ $paginator->previousPageUrl() }}" 
               tabindex="{{ $paginator->currentPage() <= 1 ? '-1' : '0' }}" 
               aria-disabled="{{ $paginator->currentPage() <= 1 ? 'true' : 'false' }}">
                <i class="bi bi-chevron-left fs-4"></i>
            </a>
        </li>

        @foreach(range(1, $paginator->lastPage()) as $page)
            <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}" aria-current="{{ $page == $paginator->currentPage() ? 'page' : '' }}">
                <a class="page-link mx-1 rounded {{ $page == $paginator->currentPage() ? 'bg-primary text-white' : 'border-0 text-dark' }}" href="{{ $paginator->url($page) }}">
                    {{ $page }}
                </a>
            </li>
        @endforeach

        <li class="page-item {{ $paginator->currentPage() >= $paginator->lastPage() ? 'disabled' : '' }}">
            <a class="page-link border-0 bg-transparent" href="{{ $paginator->nextPageUrl() }}"
               tabindex="{{ $paginator->currentPage() >= $paginator->lastPage() ? '-1' : '0' }}" 
               aria-disabled="{{ $paginator->currentPage() >= $paginator->lastPage() ? 'true' : 'false' }}">
                <i class="bi bi-chevron-right fs-4 text-primary"></i>
            </a>
        </li>
    </ul>
</nav>