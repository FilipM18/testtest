<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryPageController extends Controller
{
    public function index()
    {
        $products = [
            [
                'image' => 'images/blueGant.png',
                'title' => 'Basic Tee 8-Pack',
                'description' => 'Get the full lineup of our Basic Tees. Have a fresh shirt all week, and an extra for laundry day.',
                'colors' => 8,
                'price' => 256
            ],
            // Duplicate the same product 5 more times for a total of 6
        ];
        
        // Ensure we have 6 products
        while (count($products) < 6) {
            $products[] = $products[0];
        }
        
        return view('CategoryPage', compact('products'));
    }
}
