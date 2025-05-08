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
            'gender' => 'required|string|in:male,female,unisex',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
            'images' => 'required|array|min:1'
        ]);
        
        $variants = $request->input('variants', $request->input('edit_variants', []));

        if (empty($variants)) {
            return redirect()->back()->withInput()->withErrors(['variants' => 'At least one product variant is required']);
        }
        
        foreach ($variants as $variantData) {
            if (!isset($variantData['color']) || !isset($variantData['size']) || !isset($variantData['stock'])) {
                return redirect()->back()->withInput()->withErrors(['variants' => 'Each variant must have color, size, and stock quantity']);
            }
        }
        
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
        
        foreach ($variants as $variantData) {
            $variant = new \App\Models\ProductVariant();
            $variant->product_id = $product->product_id;
            $variant->size = $variantData['size'];
            $variant->color = $variantData['color'];
            $variant->stock_quantity = $variantData['stock'];
            $variant->sku = strtoupper(substr($product->name, 0, 3)) . '-' . 
                            strtoupper($variantData['color']) . '-' . 
                            strtoupper($variantData['size']) . '-' . 
                            $product->product_id;
            $variant->save();
        }
        
        return redirect()->route('admin.products')->with('success', 'Product created successfully');
    }
    
    // Update Product
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'brand_id' => 'required|exists:brands,brand_id',
            'gender' => 'required|string|in:male,female,unisex',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'edit_variants' => 'required|array|min:1',
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
            
            $product->image_url = json_encode($currentImages, JSON_UNESCAPED_SLASHES);
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
        
        $existingVariantIds = $product->variants->pluck('variant_id')->toArray();
        $updatedVariantIds = [];
        
        foreach ($request->edit_variants as $variantData) {
            if (!empty($variantData['variant_id'])) {
                $variant = \App\Models\ProductVariant::find($variantData['variant_id']);
                if ($variant) {
                    $variant->color = $variantData['color'];
                    $variant->size = $variantData['size'];
                    $variant->stock_quantity = $variantData['stock'];
                    $variant->sku = strtoupper(substr($product->name, 0, 3)) . '-' . 
                                    strtoupper($variantData['color']) . '-' . 
                                    strtoupper($variantData['size']) . '-' . 
                                    $product->product_id;
                    $variant->save();
                    $updatedVariantIds[] = $variant->variant_id;
                }
            } else {
                $variant = new \App\Models\ProductVariant();
                $variant->product_id = $product->product_id;
                $variant->color = $variantData['color'];
                $variant->size = $variantData['size'];
                $variant->stock_quantity = $variantData['stock'];
                $variant->sku = strtoupper(substr($product->name, 0, 3)) . '-' . 
                                strtoupper($variantData['color']) . '-' . 
                                strtoupper($variantData['size']) . '-' . 
                                $product->product_id;
                $variant->save();
                $updatedVariantIds[] = $variant->variant_id;
            }
        }
        
        $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
        if (!empty($variantsToDelete)) {
            \App\Models\ProductVariant::whereIn('variant_id', $variantsToDelete)->delete();
        }
        
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
    
    public function getProductData($id)
    {
        $product = \App\Models\Product::with(['brand', 'variants'])->findOrFail($id);

        $images = [];
        if (is_array($product->image_url)) {
            $images = $product->image_url;
        } elseif (is_string($product->image_url)) {
            $images = json_decode($product->image_url, true) ?? [];
        }

        $variants = $product->variants->map(function($variant) {
            return [
                'variant_id' => $variant->variant_id,
                'color' => $variant->color,
                'size' => $variant->size,
                'stock_quantity' => $variant->stock_quantity,
                'sku' => $variant->sku
            ];
        });

        return response()->json([
            'product_id' => $product->product_id,
            'name' => $product->name,
            'price' => $product->price,
            'brand_id' => $product->brand_id,
            'gender' => $product->gender,
            'type' => $product->type,
            'description' => $product->description,
            'images' => $images,
            'variants' => $variants
        ]);
    }
    
}