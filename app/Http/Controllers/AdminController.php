<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    // Dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    
    // Product Management
    public function products()
    {
        $products = Product::with('brand')->get();
        $brands = Brand::all();
        return view('AdminProductManagement', compact('products', 'brands'));
    }
    
    // Order Management
    public function orders()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('AdminOrderManagement', compact('orders'));
    }
    
    // Create Product Form
    public function createProduct()
    {
        $brands = Brand::all();
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Purple', 'Orange', 'Gray'];
        return view('admin.create-product', compact('brands', 'colors'));
    }
    
    // Store Product
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'brand_id' => 'required|exists:brands,brand_id',
            'gender' => 'required|string|in:Men,Women,Unisex',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/products'), $filename);
                $imagePaths[] = 'images/products/' . $filename;
            }
        }
        
        // Create product
        $product = new Product();
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->brand_id = $validated['brand_id'];
        $product->gender = $validated['gender'];
        $product->type = $validated['type'];
        $product->price = $validated['price'];
        $product->image_url = count($imagePaths) > 0 ? json_encode($imagePaths) : null;
        $product->active = true;
        $product->save();
        
        return redirect()->route('admin.products')->with('success', 'Product created successfully');
    }
    
    // Edit Product Form
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $brands = Brand::all();
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Purple', 'Orange', 'Gray'];
        
        return view('admin.edit-product', compact('product', 'brands', 'colors'));
    }
    
    // Update Product
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'brand_id' => 'required|exists:brands,brand_id',
            'gender' => 'required|string|in:Men,Women,Unisex',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $currentImages = is_string($product->image_url) ? json_decode($product->image_url, true) : [];
            if (!is_array($currentImages)) {
                $currentImages = [];
            }
            
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/products'), $filename);
                $currentImages[] = 'images/products/' . $filename;
            }
            
            $product->image_url = json_encode($currentImages);
        }
        
        // Update product
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->brand_id = $validated['brand_id'];
        $product->gender = $validated['gender'];
        $product->type = $validated['type'];
        $product->price = $validated['price'];
        $product->updated_at = null;
        $product->save();
        
        return redirect()->route('admin.products')->with('success', 'Product updated successfully');
    }
    
    public function deleteProduct($id)
{
    try {
        DB::beginTransaction();
        
        $product = Product::findOrFail($id);
        
        // 1. Delete related product variants first (to avoid foreign key constraint)
        DB::table('productvariants')->where('product_id', $id)->delete();
        
        // 2. Delete from carts if applicable
        // Check if carts table exists and has product_id column
        // 2. Delete from cart_items if applicable
        if (Schema::hasTable('cart_items')) {
            DB::table('cart_items')->where('product_id', $id)->delete();
        }

        
        // 3. Delete related reviews
        DB::table('reviews')->where('product_id', $id)->delete();
        
        // 4. Delete product images from storage
        if (!empty($product->image_url)) {
            $images = is_string($product->image_url) ? json_decode($product->image_url, true) : $product->image_url;
            
            if (is_array($images)) {
                foreach ($images as $imagePath) {
                    if (file_exists(public_path($imagePath))) {
                        unlink(public_path($imagePath));
                    }
                }
            }
        }
        
        
        $product->delete();
        
        DB::commit();
        
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('admin.products')->with('error', 'Error deleting product: ' . $e->getMessage());
    }
}


    
    public function deleteProductImage($productId, $imageIndex)
    {
        $product = Product::findOrFail($productId);
        $images = is_string($product->image_url) ? json_decode($product->image_url, true) : $product->image_url;
        
        if (is_array($images) && isset($images[$imageIndex])) {
            $imagePath = $images[$imageIndex];
            

            if (file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }
            
            // Remove from array and reindex
            unset($images[$imageIndex]);
            $images = array_values($images);
            
            // Update product
            $product->image_url = json_encode($images);
            $product->save();
            
            return redirect()->back()->with('success', 'Image deleted successfully');
        }
        
        return redirect()->back()->with('error', 'Image not found');
    }
    
    // Delete Product Image via AJAX
    public function deleteProductImageAjax(Request $request, $productId, $imageIndex)
    {
        $product = Product::findOrFail($productId);
        $images = is_string($product->image_url) ? json_decode($product->image_url, true) : $product->image_url;
        
        if (is_array($images) && isset($images[$imageIndex])) {
            $imagePath = $images[$imageIndex];
            
            // Delete the physical file
            if (file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }
            
            // Remove from array and reindex
            unset($images[$imageIndex]);
            $images = array_values($images);
            
            // Update product
            $product->image_url = json_encode($images);
            $product->save();
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Image not found']);
    }
    
    // Get Product Data for Modal
    public function getProductData($id)
    {
        $product = \App\Models\Product::with('brand')->findOrFail($id);
    
        // Format images as an array
        $images = [];
        if (is_array($product->image_url)) {
            $images = $product->image_url;
        } elseif (is_string($product->image_url)) {
            $images = json_decode($product->image_url, true) ?? [];
        }
    
        return response()->json([
            'product_id' => $product->product_id,
            'name' => $product->name,
            'price' => $product->price,
            'brand_id' => $product->brand_id,
            'gender' => $product->gender,
            'color' => $product->color,
            'type' => $product->type,
            'description' => $product->description,
            'images' => $images,
        ]);
    }
    
}