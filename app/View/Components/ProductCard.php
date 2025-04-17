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
    public $id; // Add this property

    public function __construct($image, $title, $description, $colors, $price, $id, $url = null)
    {
        $this->image = $image;
        $this->title = $title;
        $this->description = $description;
        $this->colors = $colors;
        $this->price = $price;
        $this->id = $id; // Store the ID
        $this->url = $url ?? route('ProductInfo.show', $id); // Use the products.show route with the ID
    }

    public function render()
    {
        return view('components.product-card');
    }
}
