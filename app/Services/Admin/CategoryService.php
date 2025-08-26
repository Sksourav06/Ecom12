<?php

namespace App\Services\Admin;
use App\Models\Category;
use App\Models\AdminsRole;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
class CategoryService
{
    public function Categories()
    {
        $categories = Category::with('parentcategory')->get();
        $admin = Auth::guard('admin')->user();
        $status = "success";
        $message = "";
        $categoriesModule = [];

        // admin has full access
        if ($admin->role == "admin") {
            $categoriesModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1
            ];
        } else {
            $categoriesModuleCount = AdminsRole::where([
                'subadmin_id' => $admin->id,
                'model' => 'categories'
            ])->count();

            if ($categoriesModuleCount == 0) {
                $status = 'error';
                $message = 'This feature is restricted for you!';
            } else {
                $categoriesModule = AdminsRole::where([
                    'subadmin_id' => $admin->id,
                    'model' => 'categories'
                ])->first()->toArray();
            }
        }

        return [
            'categories' => $categories,
            'categoriesModule' => $categoriesModule,
            'status' => $status,
            'message' => $message
        ];
    }

    public function addEditCategory($request)
{
    $data = $request->all();

    if (isset($data['id']) && $data['id'] != '') {
        // Edit Category
        $category = Category::find($data['id']);
        $message = "Category updated successfully!";
    } else {
        // Add Category
        $category = new Category;
        $message = "Category added successfully!";
    }

    $category->parent_id = !empty($data['parent_id']) ? $data['parent_id'] : null;

    // Upload category image
    if ($request->hasFile('category_image')) {
        $image_tmp = $request->file('category_image');
        if ($image_tmp->isValid()) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image_tmp);
            $extension = $image_tmp->getClientOriginalExtension();
            $imageName = rand(111, 99999) . '.' . $extension;
            $image_path = public_path('front/images/categories/' . $imageName);

            // Ensure directory exists
            File::ensureDirectoryExists(dirname($image_path));

            $image->save($image_path);
            $category->image = $imageName;
        }
    }

    // Upload size chart
    if ($request->hasFile('size_chart')) {
        $sizechart_tmp = $request->file('size_chart');
        if ($sizechart_tmp->isValid()) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sizechart_tmp);
            $extension = $sizechart_tmp->getClientOriginalExtension();
            $sizechartName = rand(111, 99999) . '.' . $extension;
            $sizechart_path = public_path('front/images/sizechart/' . $sizechartName);

            File::ensureDirectoryExists(dirname($sizechart_path));

            $image->save($sizechart_path);
            $category->size_chart = $sizechartName;
        }
    }

    // Format category name & url
    $data['category_name'] = ucwords(str_replace('_', ' ', strtolower($data['category_name'])));
    $data['url'] = str_replace(" ", "_", strtolower($data['url']));

    $category->name = $data['category_name'];
    $category->discount = $data['category_discount'] ?? 0;
    $category->description = $data['description'];
    $category->url = $data['url'];
    $category->meta_title = $data['meta_title'];
    $category->meta_description = $data['meta_description'];
    $category->meta_keywords = $data['meta_keywords'];

    // Menu Status
    $category->menu_status = !empty($data['menu_status']) ? 1 : 0;

    // Status
    $category->status = 1;

    $category->save();

    return $message;
}
  public function updateCategoryStatus($data)
    {
        // স্ট্যাটাস টগল করা: যদি Active হয় তবে Inactive হয়ে যাবে, এবং vice versa
        $status = ($data['status'] == 'Active') ? 0 : 1;

        // ডাটাবেজে সাবঅ্যাডমিনের স্ট্যাটাস আপডেট করা
        $updated = Category::where('id', $data['category_id'])->update(['status' => $status]);

        return $status;  // নতুন স্ট্যাটাস রিটার্ন করা হচ্ছে
    }
   public function deleteCategory($id)
   {
    Category::where('id',$id)->delete();
    $message = 'Category deleted successfully!';
    return['message'=>$message];
   }

   public function deleteCategoryImage($categoryId)
{
    $categoryImage = Category::where('id', $categoryId)->value('image');

    if ($categoryImage) {
        $category_image_path = 'front/images/categories/' . $categoryImage;

        if (file_exists(public_path($category_image_path))) {
            unlink(public_path($category_image_path));
        }

        Category::where('id', $categoryId)->update(['image' => null]);

        return [
            'status' => true,
            'message' => 'Category image deleted successfully!',
        ];
    }

    return [
        'status' => false,
        'message' => 'Category image not found.',
    ];
}

}
