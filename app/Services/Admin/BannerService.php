<?php
namespace App\Services\Admin;
use App\Models\AdminsRole;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BannerService
{
    public function Banners()
    {
        $banners = Banner::get();
        $admin = Auth::guard('admin')->user();
        $banners = Banner::orderBy('sort', 'asc')->get();
        $status = "success";
        $message = "";
        $bannersModule = [];

        // admin has full access
        if ($admin->role == "admin") {
            $bannersModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1
            ];
        } else {
            $bannersModule = AdminsRole::where([
                'subadmin_id' => $admin->id,
                'model' => 'banners'
            ])->count();

            if ($bannersModule == 0) {
                $status = 'error';
                $message = 'This feature is restricted for you!';
            } else {
                $bannersModule = AdminsRole::where([
                    'subadmin_id' => $admin->id,
                    'model' => 'banners'
                ])->first()->toArray();
            }
        }

        return [
            'banners' => $banners,
            'bannersModule' => $bannersModule,
            'status' => $status,
            'message' => $message
        ];
    }
    public function updateBannerStatus($data)
    {
        // স্ট্যাটাস টগল করা: যদি Active হয় তবে Inactive হয়ে যাবে, এবং vice versa
        $status = ($data['status'] == 'Active') ? 0 : 1;

        // ডাটাবেজে স্ট্যাটাস আপডেট করা
        $updated = Banner::where('id', $data['banner_id'])->update(['status' => $status]);

        return $status;  // নতুন স্ট্যাটাস রিটার্ন করা হচ্ছে
    }

    public function daleteBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $bannerImagePath = public_path('front/images/banners' . $banner->image);
        if (File::exists($bannerImagePath)) {
            File::delete($bannerImagePath);
        }

        $banner->delete();

        return ['status' => 'success', 'message' => 'Banner delete successfully'];
    }

    public function addEditBanner($request)
    {
        $data = $request->all();
        $banner = isset($data['id']) ? Banner::find($data['id']) : new Banner();

        $banner->type = $data['type'];
        $banner->link = $data['link'];
        $banner->title = $data['title'];
        $banner->alt = $data['alt'];
        $banner->sort = $data['sort'] ?? 0;
        $banner->status = $data['status'] ?? 1;

        if ($request->hasFile('image')) {
            $path = 'front/images/banners/';
            if (!File::exists(public_path($path))) {
                File::makeDirectory(public_path($path), 0755, true);
            }

            //delete
            if (!empty($banner->image) && File::exists(public_path($path . $banner->image))) {
                File::delete(public_path($path . $banner->image));
            }

            $image = $request->file('image');
            $imageName = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path($path), $imageName);
            $banner->image = $imageName;
        }

        $banner->save();
        return isset($data['id']) ? 'Banner update successfully!' : 'Banner added successfully';
    }
}
