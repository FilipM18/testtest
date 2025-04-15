<div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="filterOffcanvasLabel">Filters</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form>
            <x-filter-section heading="Color" :options="$colorOptions" mobile="Mobile" />
            <x-filter-section heading="Category" :options="$categoryOptions" mobile="Mobile" />
            <x-filter-section heading="Sizes" :options="$sizeOptions" mobile="Mobile" />
        </form>
    </div>
</div>
