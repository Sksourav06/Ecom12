<?php
namespace App\Services\Admin;
use App\Models\AdminsRole;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BrandService
{
    public function Brands()
    {
        $brands = Brand::get();
        $admin = Auth::guard('admin')->user();
        $status = "success";
        $message = "";
        $brandsModule = [];

        // admin has full access
        if ($admin->role == "admin") {
            $brandsModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1
            ];
        } else {
            $brandsModuleCount = AdminsRole::where([
                'subadmin_id' => $admin->id,
                'model' => 'brands'
            ])->count();

            if ($brandsModuleCount == 0) {
                $status = 'error';
                $message = 'This feature is restricted for you!';
            } else {
                $brandsModule = AdminsRole::where([
                    'subadmin_id' => $admin->id,
                    'model' => 'brands'
                ])->first()->toArray();
            }
        }

        return [
            'brands' => $brands,
            'brandsModule' => $brandsModule,
            'status' => $status,
            'message' => $message
        ];
    }
    public function updateBrandStatus($data)
    {
        // স্ট্যাটাস টগল করা: যদি Active হয় তবে Inactive হয়ে যাবে, এবং vice versa
        $status = ($data['status'] == 'Active') ? 0 : 1;

        // ডাটাবেজে সাবঅ্যাডমিনের স্ট্যাটাস আপডেট করা
        $updated = Brand::where('id', $data['brand_id'])->update(['status' => $status]);

        return $status;  // নতুন স্ট্যাটাস রিটার্ন করা হচ্ছে
    }

    public function addEditBrand($request)
    {
        $data = $request->all();
        if (isset($data['id']) && $data['id'] != "") {
            //Eidit Brand
            $brand = Brand::find($data['id']);
            $message = "Brand update successfully";
        } else {
            //add Brand
            $brand = new Brand;
            $message = "Brand added successfully!";
        }
        //upload image
        if ($request->hasFile('image')) {
            $image_tmp = $request->file('image');
            if ($image_tmp->isvalid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image_tmp);
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(111, 99999) . '.' . $extension;
                $image_path = 'front/images/brands/' . $imageName;
                $image->save($image_path);
                $brand->image = $imageName;
            }
        }
        //upload logo
        if ($request->hasFile('logo')) {
            $sizechart_tmp = $request->file('logo');
            if ($sizechart_tmp->isvalid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($sizechart_tmp);
                $sizechart_extension = $sizechart_tmp->getClientOriginalExtension();
                $sizetimsgeName = rand(111, 99999) . '.' . $sizechart_extension;
                $sizechart_image_path = 'front/images/logos/' . $sizetimsgeName;
                $image->save($sizechart_image_path);
                $brand->logo = $sizetimsgeName;
            }
        }

        //format

        $data['name'] = ucwords(str_replace('-', ' ', strtolower($data['name'])));
        $data['url'] = str_replace(" ", "-", strtolower($data['url']));

        $brand->name = $data['name'];

        //discount
        if (empty($data['brand_discount'])) {
            $data['brand_discount'] = 0;
        }
        $brand->discount = $data['brand_discount'];
        $brand->description = $data['description'];
        $brand->url = $data['url'];
        $brand->meta_title = $data['meta_title'];
        $brand->meta_description = $data['meta_description'];
        $brand->meta_keywords = $data['meta_keywords'];

        //menu status
        if (!empty($data['menu_status'])) {
            $brand->menu_status = 1;
        } else {
            $brand->menu_status = 0;
        }

        //status

        $brand->status = 1;
        $brand->save();
        return $message;
    }

    public function deleteBrand($id)
    {
        Brand::where('id', $id)->delete();
        $message = 'Brand deleted successfully!';
        return ['message' => $message];
    }
}
