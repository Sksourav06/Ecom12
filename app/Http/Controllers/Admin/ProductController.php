<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\ColumnPreference;
use App\Models\ProductsImage;
use Illuminate\Support\Facades\Auth;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Session::put('page', 'products');
        $result = $this->productService->product();
        if ($result['status'] == 'error') {
            return redirect('admin/dashboard')->with('error_message', $result['message']);
        }
        $products = $result['products'];
        $productsModule = $result['productsModule'];
        $columnPrefs = ColumnPreference::where('admin_id', Auth::guard('admin')->id())
            ->where('table_name', 'products')
            ->first();
        $productsSavedOrder = $columnPrefs ? json_decode($columnPrefs->column_order, true) : null;
        $productsHiddenCols = $columnPrefs ? json_decode($columnPrefs->hidden_columns, true) : [];

        return view('admin.products.index')->with(compact(
            'products',
            'productsModule',
            'productsSavedOrder',
            'productsHiddenCols'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add Product';
        $getCategories = Category::getCategories('Admin');
        $brands = Brand::where('status', 1)->get()->toArray();
        return view('admin.products.add_edit_product', compact('title', 'getCategories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $message = $this->productService->addEditProduct($request);
        return redirect()->route('product.index')->with('success_message', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "Edit Product";

        $product = Product::findOrFail($id);
        $product = Product::with('product_images', 'attributes')->findOrFail($id);
        $getCategories = Category::getCategories('Admin');
        $brands = Brand::where('status', 1)->get()->toArray();
        return view('admin.products.add_edit_product', compact('title', 'product', 'getCategories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $request->merge(['id' => $id]);
        $product = Product::findOrFail($id);
        // Update product fields...

        $product->save();

        // Save uploaded images
        $this->saveProductImages($request, $product->id);
        $message = $this->productService->addEditProduct($request);
        return redirect()->route('product.index')->with('success_message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->productService->deleteProduct($id);
        return redirect()->back()->with('success_message', $result['message']);
    }

    public function updateProductStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $status = $this->productService->updateProductStatus($data);
            return response()->json(['status' => $status, 'product_id' => $data['product_id']]);
        }
    }
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            $file = $request->file('file');
            $fileName = $this->productService->handleImageUpload($file);

            return response()->json([
                'status' => true,
                'file_name' => $fileName,
                'message' => 'Image uploaded successfully.',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No image uploaded.',
        ], 400);
    }
    public function uploadProductVideo(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $request->validate([
                'file' => 'required|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:20480',
            ]);

            $fileName = $this->productService->handleVideoUpload($file);

            return response()->json([
                'status' => true,
                'file_name' => $fileName,
                'message' => 'Video uploaded successfully.',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No video uploaded.',
        ], 400);
    }

    public function deleteProductMainImage(Request $request)
    {
        $id = $request->id;
        $message = $this->productService->deleteProductMainImage($id);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
    public function uploadImages(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('temp'), $fileName);

            return response()->json(['image' => $fileName]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function saveProductImages(Request $request, $productId)
    {
        $data = $request->all();

        if (!empty($data['product_images'])) {
            $imageFiles = is_array($data['product_images'])
                ? $data['product_images']
                : json_decode($data['product_images'], true);

            $imageFiles = array_filter($imageFiles);

            foreach ($imageFiles as $index => $filename) {
                $sourcePath = public_path('temp/' . $filename);
                $destinationPath = public_path('front/images/product/' . $filename);

                if (file_exists($sourcePath)) {
                    copy($sourcePath, $destinationPath);
                    unlink($sourcePath);
                }

                ProductsImage::create([
                    'product_id' => $productId,
                    'image' => $filename,
                    'sort' => $index,
                    'status' => 1,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Product images saved!');
    }

    public function deleteImage(Request $request)
    {
        $image = $request->input('image');

        // Delete from temp or front/images/product
        $tempPath = public_path('temp/' . $image);
        $frontPath = public_path('front/images/product/' . $image);

        if (file_exists($tempPath)) {
            unlink($tempPath);
        }

        if (file_exists($frontPath)) {
            unlink($frontPath);
        }

        // Optionally delete from DB if image linked to product
        ProductsImage::where('image', $image)->delete();

        return response()->json(['success' => true]);
    }
    public function deleteProductAttribut($id)
    {
        $message = $this->productService->deleteProductAttribute($id);
        return redirect()->back()->with('success_message', $message);
    }

}

//     public function uploadVideo(Request $request){
//         if($request->hasFile('file')){
//             $fileName = $this->productService->handleVideoUpload($request->files('file'));
//             return response()->json(['fileName'=>$fileName]);
//         }
//         return response()->json(['error' =>'No file upload'],400);
//     }

//     public function deleteProductMainImage($id){
//          $message = $this->productService->deleteProductMainImage($id);
//          return redirect()->back()->with('success_message',$message);
//     }
// }

