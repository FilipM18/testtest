<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CategoryPageController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $category = $request->query('category');
        $color = $request->query('color');
        $size = $request->query('size');
        
        // Start with a base query
        $query = Product::query()->where('active', true);
        
        // Apply category filter if provided
        if ($category && $category !== 'all-new') {
            $query->where('type', $category);
        }
        
        // Apply color filter if provided (requires join with variants)
        if ($color) {
            $query->whereHas('variants', function($q) use ($color) {
                $q->where('color', $color);
            });
        }
        
        // Apply size filter if provided (requires join with variants)
        if ($size) {
            $query->whereHas('variants', function($q) use ($size) {
                $q->where('size', $size);
            });
        }
        
        // Pagination settings
        $perPage = 6;
        $products = $query->paginate($perPage);
        
        // Get filter options from database or use static lists
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
        
        // Get current query parameters for pagination links
        $queryParams = request()->query();
        
        return view('CategoryPage', compact(
            'products', 
            'colorOptions', 
            'categoryOptions', 
            'sizeOptions',
            'queryParams'
        ));
    }
}
