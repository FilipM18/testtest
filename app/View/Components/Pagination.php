<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Pagination extends Component
{
    public $currentPage;
    public $totalPages;
    public $route;
    public $queryParams;


    public function __construct($currentPage = 1, $totalPages = 1, $route = null, $queryParams = [])
    {
        $this->currentPage = $currentPage;
        $this->totalPages = $totalPages;
        $this->route = $route;
        $this->queryParams = $queryParams;
    }

    public function render(): View|Closure|string
    {
        return view('components.pagination');
    }


    public function isValidPage($page): bool
    {
        return $page >= 1 && $page <= $this->totalPages;
    }

    
    public function getPageUrl($page): string
    {
        if ($this->route) {
            $params = array_merge(['page' => $page], $this->queryParams);
            return route($this->route, $params);
        }
        
        
        $queryParams = array_merge(['page' => $page], $this->queryParams);
        $query = http_build_query($queryParams);
        $baseUrl = url()->current();
        
        return $baseUrl . '?' . $query;
    }

    
    public function getPageNumbers(): array
    {
        // Show max 5 page numbers
        $maxPages = 5;
        $pages = [];
        
        if ($this->totalPages <= $maxPages) {
            $pages = range(1, $this->totalPages);
            
        } else {
            $halfMax = floor($maxPages / 2);
            
            if ($this->currentPage <= $halfMax + 1) {
                $pages = range(1, $maxPages);

            } elseif ($this->currentPage >= $this->totalPages - $halfMax) {
                $pages = range($this->totalPages - $maxPages + 1, $this->totalPages);

            } else {
                $pages = range($this->currentPage - $halfMax, $this->currentPage + $halfMax);
            }
        }
        
        return $pages;
    }
}