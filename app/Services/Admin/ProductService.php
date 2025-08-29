<?php
namespace App\Services\Admin;
use App\Models\Product;
use App\Models\AdminsRole;
use App\Models\Category;
use App\Models\ProductsAttribute;
use App\Models\ProductsImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductService
{
    public function product()
    {
        $products = Product::with('category')->get();

        $productsModuleCount = AdminsRole::where([
            'subadmin_id' => Auth::guard('admin')->user()->id,
            'model' => 'products'
        ])->count();

        $status = 'success';
        $message = "";
        $productsModule = [];

        if (Auth::guard('admin')->user()->role == 'admin') {
            $productsModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1
            ];
        } elseif ($productsModuleCount == 0) {
            $status = 'error';
            $message = 'This feature is restricted for you!';
        } else {
            $productsModule = AdminsRole::where([
                'subadmin_id' => Auth::guard('admin')->user()->id,
                'model' => 'products'
            ])->first()->toArray();
        }

        return [
            'products' => $products,
            'productsModule' => $productsModule,
            'status' => $status,
            'message' => $message
        ];
    }

    public function updateProductStatus($data)
    {
        // স্ট্যাটাস টগল করা: যদি Active হয় তবে Inactive হয়ে যাবে, এবং vice versa
        $status = ($data['status'] == 'Active') ? 0 : 1;

        // ডাটাবেজে সাবঅ্যাডমিনের স্ট্যাটাস আপডেট করা
        $updated = Product::where('id', $data['product_id'])->update(['status' => $status]);

        return $status;  // নতুন স্ট্যাটাস রিটার্ন করা হচ্ছে
    }
    public function deleteProduct($id)
    {
        Product::where('id', $id)->delete();
        $message = 'Product deleted successfully!';
        return ['message' => $message];
    }

    public function addEditProduct($request)
    {
        $data = $request->all();

        $safeImplode = function ($input) {
            return is_array($input) ? implode(', ', array_map('trim', array_filter($input, 'is_scalar'))) : trim((string) $input);
        };

        // Check if editing or adding new
        if (!empty($data['id'])) {
            $product = Product::find($data['id']);
            $message = "Product updated successfully";
        } else {
            $product = new Product;
            $message = "Product added successfully";
        }

        // Assign basic fields
        $product->admin_id = Auth::guard('admin')->user()->id;
        $product->admin_role = Auth::guard('admin')->user()->role;
        $product->category_id = $data['category_id'];
        $product->brand_id = $data['brand_id'];
        $product->product_name = $data['product_name'];
        $product->product_code = $data['product_code'];
        $product->product_color = $data['product_color'];
        $product->family_color = $data['family_color'];
        $product->group_code = $data['group_code'];
        $product->product_weight = $data['product_weight'];
        $product->product_price = $data['product_price'];
        $product->product_gst = $data['product_gst'];
        $product->product_discount = $data['product_discount'];
        $product->is_featured = $data['is_featured'] ?? 'No';

        // Calculate discount
        if (!empty($data['product_discount']) && $data['product_discount'] > 0) {
            $product->discount_applied_on = 'product';
            $product->product_discount_amount = ($data['product_price'] * $data['product_discount']) / 100;
        } else {
            $categoryDiscount = Category::select('discount')->where('id', $data['category_id'])->first();
            if ($categoryDiscount && $categoryDiscount->discount > 0) {
                $product->discount_applied_on = 'category';
                $product->product_discount = $categoryDiscount->discount;
                $product->product_discount_amount = ($data['product_price'] * $categoryDiscount->discount) / 100;
            } else {
                $product->discount_applied_on = '';
                $product->product_discount_amount = 0;
            }
        }

        $product->final_price = $data['product_price'] - $product->product_discount_amount;

        // Description and meta
        $product->description = $safeImplode($data['description'] ?? '');
        $product->wash_care = $safeImplode($data['wash_care'] ?? '');
        $product->search_keywords = $safeImplode($data['search_keywords'] ?? '');
        $product->meta_title = $safeImplode($data['meta_title'] ?? '');
        $product->meta_keywords = $safeImplode($data['meta_keywords'] ?? '');
        $product->meta_description = $safeImplode($data['meta_description'] ?? '');

        // Stock and sort
        $product->stock = is_array($data['stock'] ?? null) ? 0 : (int) ($data['stock'] ?? 0);
        $product->sort = is_array($data['sort'] ?? null) ? 0 : (int) ($data['sort'] ?? 0);
        $product->status = 1;

        // Upload and move main image
        if (!empty($data['main_image_hidden'])) {
            $filename = $data['main_image_hidden'];
            $sourcePath = public_path('temp/' . $filename);
            $destinationPath = public_path('product-image/medium/' . $filename);

            if (file_exists($sourcePath)) {
                if (!file_exists(dirname($destinationPath))) {
                    mkdir(dirname($destinationPath), 0775, true);
                }

                copy($sourcePath, $destinationPath);
                unlink($sourcePath);
                $product->main_image = $filename;
            }
        }

        // Upload product video
        if (!empty($data['product_video_hidden'])) {
            $filename = $data['product_video_hidden'];
            $sourcePath = public_path('temp/' . $filename);
            $destinationPath = public_path('front/videos/product/' . $filename);

            if (file_exists($sourcePath)) {
                if (!file_exists(dirname($destinationPath))) {
                    mkdir(dirname($destinationPath), 0775, true);
                }

                copy($sourcePath, $destinationPath);
                unlink($sourcePath);
                $product->product_video = $filename;
            }
        }

        // Save product first to get ID
        $product->save();

        if (!empty($data['filter_values'])&& is_array($data['filter_values'])) {
            $values = array_values(array_filter($data['filter_values']));
            $product->filterValues()->sync($values);
        }else{
            $product->filterValues()->detach();
        }

        // ✅ Save multiple images
        if ($request->filled('product_images')) {
            $imageFiles = json_decode($request->input('product_images'), true);

            foreach ($imageFiles as $index => $filename) {
                $sourcePath = public_path('temp/' . $filename);
                $destinationPath = public_path('product-image/medium/' . $filename);

                if (file_exists($sourcePath)) {
                    if (!file_exists(dirname($destinationPath))) {
                        mkdir(dirname($destinationPath), 0775, true);
                    }

                    copy($sourcePath, $destinationPath);
                    unlink($sourcePath);
                }

                // 🔒 Prevent duplicate insert
                $exists = ProductsImage::where('product_id', $product->id)
                    ->where('image', $filename)
                    ->exists();

                if (!$exists) {
                    ProductsImage::create([
                        'product_id' => $product->id,
                        'image' => $filename,
                        'sort' => $index,
                        'status' => 1
                    ]);
                }
            }
        }

        // Handle attributes
        $total_stock = 0;

        if (!empty($data['sku']) && is_array($data['sku'])) {
            foreach ($data['sku'] as $key => $sku) {
                $size = $data['size'][$key] ?? null;
                $price = $data['price'][$key] ?? null;
                $stock = (int) ($data['stock'][$key] ?? 0);
                $sort = $data['sort'][$key] ?? 0;

                if (empty($sku) || empty($size) || empty($price))
                    continue;
                if (is_array($sku) || is_array($size) || is_array($price))
                    continue;

                $duplicateSKU = ProductsAttribute::where('sku', $sku)->exists();
                if ($duplicateSKU)
                    return ['status' => false, 'message' => "SKU '{$sku}' already exists."];

                $duplicateSize = ProductsAttribute::where([
                    ['product_id', '=', $product->id],
                    ['size', '=', $size]
                ])->exists();
                if ($duplicateSize)
                    return ['status' => false, 'message' => "Size '{$size}' already exists."];

                ProductsAttribute::create([
                    'product_id' => $product->id,
                    'sku' => $sku,
                    'size' => $size,
                    'price' => $price,
                    'stock' => $stock,
                    'sort' => $sort,
                    'status' => 1
                ]);

                $total_stock += $stock;
            }
        }

        if (!empty($data['attrId']) && is_array($data['attrId'])) {
            foreach ($data['attrId'] as $key => $attrId) {
                if (!empty($attrId)) {
                    $price = $data['update_price'][$key] ?? null;
                    $stock = (int) ($data['update_stock'][$key] ?? 0);
                    $sort = $data['update_sort'][$key] ?? 0;

                    if (is_null($price))
                        continue;

                    ProductsAttribute::where('id', $attrId)->update([
                        'price' => $price,
                        'stock' => $stock,
                        'sort' => $sort
                    ]);

                    $total_stock += $stock;
                }
            }
        }

        Product::where('id', $product->id)->update(['stock' => $total_stock]);

        return $message;
    }


    public function handleImageUpload($file)
    {
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('temp'), $imageName); // Temporary folder
        return $imageName;
    }

    public function handleVideoUpload($file)
    {
        $videoName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('temp'), $videoName); // Temporarily save in "temp"
        return $videoName;
    }
    public function deleteProductMainImage($id)
    {
        // Product খুঁজে বের করো
        $product = Product::findOrFail($id);

        // পুরানো ইমেজের নাম
        $imagePath = public_path('front/images/product/' . $product->main_image);

        // ফাইল থাকলে delete করো
        if (!empty($product->main_image) && file_exists($imagePath)) {
            @unlink($imagePath);
        }

        // ডাটাবেস থেকে ফিল্ড খালি করো
        $product->main_image = null;
        $product->save();

        return 'Product main image deleted successfully.';
    }

    public function uploadProductImages($image)
    {
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        // Save to temp folder inside public/
        $image->move(public_path('temp'), $imageName);

        return $imageName;
    }

    public function deleteProductImages($imageName)
    {
        $path = public_path('front/images/product/' . $imageName);
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    public function deleteProductAttribute($id)
    {
        ProductsAttribute::where('id', $id)->delete();
        return "Product Attribute has been delete successfully";
    }

}

