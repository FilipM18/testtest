<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryPageController extends Controller
{
    public function index(Request $request)
    {
        $baseProducts = [
            [
                'image' => 'images/blueGant.png',
                'title' => 'Basic Tee 8-Pack',
                'description' => 'Get the full lineup of our Basic Tees. Have a fresh shirt all week, and an extra for laundry day.',
                'colors' => 8,
                'price' => 256
            ],
        ];
        
        // Create more products for pagination demo
        $allProducts = [];
        for ($i = 0; $i < 20; $i++) {
            $product = $baseProducts[0];
            $product['title'] = 'Basic Tee ' . ($i + 1) . '-Pack';
            $allProducts[] = $product;
        }
        
        // Pagination settings
        $perPage = 6;
        $currentPage = (int) $request->query('page', 1);
        $totalProducts = count($allProducts);
        $totalPages = ceil($totalProducts / $perPage);
        
        
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;
        
        $offset = ($currentPage - 1) * $perPage;
        $products = array_slice($allProducts, $offset, $perPage);
        
        $colorOptions = [
            ['value' => 'white', 'label' => 'White', 'id' => 'colorWhite'],
            ['value' => 'beige', 'label' => 'Beige', 'id' => 'colorBeige'],
            ['value' => 'blue', 'label' => 'Blue', 'id' => 'colorBlue'],
            ['value' => 'brown', 'label' => 'Brown', 'id' => 'colorBrown'],
            ['value' => 'green', 'label' => 'Green', 'id' => 'colorGreen'],
            ['value' => 'purple', 'label' => 'Purple', 'id' => 'colorPurple'],
        ];

        $categoryOptions = [
            ['value' => 'all-new', 'label' => 'All New Arrivals', 'id' => 'categoryAllNew'],
            ['value' => 'tees', 'label' => 'Tees', 'id' => 'categoryTees'],
            ['value' => 'crewnecks', 'label' => 'Crewnecks', 'id' => 'categoryCrewnecks'],
            ['value' => 'sweatshirts', 'label' => 'Sweatshirts', 'id' => 'categorySweatshirts'],
            ['value' => 'pants', 'label' => 'Pants & Shorts', 'id' => 'categoryPants'],
        ];

        $sizeOptions = [
            ['value' => 'xs', 'label' => 'XS', 'id' => 'sizeXS'],
            ['value' => 's', 'label' => 'S', 'id' => 'sizeS'],
            ['value' => 'm', 'label' => 'M', 'id' => 'sizeM'],
            ['value' => 'l', 'label' => 'L', 'id' => 'sizeL'],
            ['value' => 'xl', 'label' => 'XL', 'id' => 'sizeXL'],
            ['value' => '2xl', 'label' => '2XL', 'id' => 'size2XL'],
        ];

        return view('CategoryPage', compact(
            'products', 
            'colorOptions', 
            'categoryOptions', 
            'sizeOptions',
            'currentPage',
            'totalPages'
        ));
    }
}