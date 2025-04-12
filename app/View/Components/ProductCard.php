<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductCard extends Component
{
    public $image;
    public $title;
    public $description;
    public $colors;
    public $price;
    public $url;

    public function __construct($image, $title, $description, $colors, $price, $url = null)
    {
        $this->image = $image;
        $this->title = $title;
        $this->description = $description;
        $this->colors = $colors;
        $this->price = $price;
        $this->url = $url ?? route('ProductInfo');
    }

    public function render()
    {
        return view('components.product-card');
    }
}
