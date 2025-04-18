<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryPageController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $categories = $request->input('category', []);
        $colors = $request->input('color', []);
        $sizes = $request->input('sizes', []);
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $sort = $request->input('sort');
        $search = $request->input('search');
        
        if (!is_array($categories)) $categories = [$categories];
        if (!is_array($colors)) $colors = [$colors];
        if (!is_array($sizes)) $sizes = [$sizes];
        
        $categories = array_filter($categories);
        $colors = array_filter($colors);
        $sizes = array_filter($sizes);
        
        $query = Product::query()->where('active', true);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%")
                  ->orWhere('type', 'ILIKE', "%{$search}%");
            });
        }        

        // Category filter
        if (!empty($categories)) {
            if (!in_array('all', $categories)) {
                $query->whereIn('type', $categories);
            }
        }
        
        // Color filter
        if (!empty($colors)) {
            $query->whereHas('variants', function($q) use ($colors) {
                $q->whereIn('color', $colors);
            });
        }
        
        // Size filter
        if (!empty($sizes)) {
            $query->whereHas('variants', function($q) use ($sizes) {
                $q->whereIn('size', $sizes);
            });
        }
        
        // Price filter
        if ($priceMin !== null && is_numeric($priceMin)) {
            $query->where('price', '>=', $priceMin);
        }
        
        if ($priceMax !== null && is_numeric($priceMax)) {
            $query->where('price', '<=', $priceMax);
        }
        
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                // Default sorting, you can define what this should be
                $query->orderBy('name', 'asc');
                break;
        }

        $perPage = 6;
        $products = $query->paginate($perPage)->appends($request->query());
        
        // Color options
        $colorOptions = ProductVariant::select('color')
            ->distinct()
            ->whereNotNull('color')
            ->where('color', '!=', '')
            ->orderBy('color')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->color,
                    'label' => ucfirst($item->color),
                    'id' => 'color' . ucfirst($item->color)
                ];
            })
            ->toArray();
            
        // Category options
        $categoryOptions = Product::select('type')
            ->distinct()
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->orderBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->type,
                    'label' => ucfirst($item->type),
                    'id' => 'category' . ucfirst($item->type)
                ];
            })
            ->toArray();
        
        array_unshift($categoryOptions, [
            'value' => 'all',
            'label' => 'All Products',
            'id' => 'categoryAll'
        ]);
            
        $sizeOptions = ProductVariant::select('size')
            ->distinct()
            ->whereNotNull('size')
            ->where('size', '!=', '')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->size,
                    'label' => strtoupper($item->size),
                    'id' => 'size' . strtoupper($item->size)
                ];
            })
            ->sortBy(function ($item) {
                $sizeOrder = [
                    's' => 1,
                    'm' => 2,
                    'l' => 3,
                    'xl' => 4,
                    '2xl' => 5,
                ];
                
                return $sizeOrder[$item['value']] ?? 999; 
            })
            ->values() 
            ->toArray();
        
        $priceRange = Product::where('active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();
            
        return view('CategoryPage', compact(
            'products', 
            'colorOptions', 
            'categoryOptions', 
            'sizeOptions',
            'priceRange'
        ));
    }
}