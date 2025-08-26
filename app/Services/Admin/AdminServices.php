<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\DetailRequest;
use App\Models\Admin;
use App\Models\AdminsRole;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Image\Laravel\Facades\Image;

class AdminServices{
    public function login($data){
      $admin = Admin::where('email', $data['email'])->first();

    if (!$admin) {
        return "Invalid";
    }

    if ($admin->status == 0) {
        return "inactive";
    }

    if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 1])) {
        if (!empty($data['remember'])) {
            setcookie('email', $data['email'], time() + 3600);
            setcookie('password', $data['password'], time() + 3600);
        } else {
            setcookie("email", "");
            setcookie("password", "");
        }
        return "success";
    }

    return "invalid";
}

      public function verifyPassword($data)
      {
        if(Hash::check($data['current_pwd'],Auth::guard('admin')->user()->password)){
            return 'true';
        }else{
            return 'false';
        }
    }
   public function updatePassword($data){
    if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
        if ($data['new_pwd'] === $data['confirm_pwd']) {
            Admin::where('email', Auth::guard('admin')->user()->email)
                ->update(['password' => bcrypt($data['new_pwd'])]);
            $status = 'success';
            $message = 'Password has been updated successfully';
        } else {
            $status = 'error';
            $message = 'New Password and Confirm Password must match';
        }
    } else {
        $status = 'error';
        $message = 'Your current password is incorrect!';
    }

    return ['status' => $status, 'message' => $message];
}
public function updateDetails($request)
{
    $data = $request->all();

    // Handle the image upload
    if ($request->hasFile('image')) {
        // If an image is uploaded
        $image_tmp = $request->file('image');
        $manager = new ImageManager(new Driver());
        $image = $manager->read($image_tmp);
        $extension = $image_tmp->getClientOriginalExtension();
        $imageName = rand(111, 99999) . '.' . $extension;
        $image_path = 'admin/images/photos/' . $imageName;
        $image->save($image_path);
    } elseif (!empty($data['current_image'])) {
        // If no image uploaded, use the current image
        $imageName = $data['current_image'];
    } else {
        // If no image or current image is provided, set an empty value
        $imageName = '';
    }

    // Update the admin details
    Admin::where('email', Auth::guard('admin')->user()->email)
        ->update([
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'image' => $imageName
        ]);
   }
   public function deleteProfileImage($adminid)
   {
    $profileImage = Admin::where('id', $adminid)->value('image');
    if ($profileImage) {
        $profile_image_path='admin/images/photos/'. $profileImage;
        if (file_exists(public_path( $profile_image_path ))) {
            unlink(public_path( $profile_image_path ));
        }
        Admin::where('id', $adminid)->update(['image'=>null]);
        return ['status'=>true,'message'=>'Profile image deleted successfully!'];
    }
    return ['status'=>false,'message'=> 'Profile image not found'];
   }

   public function subadmins()
   {
    $subadmins = Admin::where('role','subadmin')->get();
    return $subadmins;
   }

   public function updateSubadminStatus($data)
    {
        // স্ট্যাটাস টগল করা: যদি Active হয় তবে Inactive হয়ে যাবে, এবং vice versa
        $status = ($data['status'] == 'Active') ? 0 : 1;

        // ডাটাবেজে সাবঅ্যাডমিনের স্ট্যাটাস আপডেট করা
        $updated = Admin::where('id', $data['subadmin_id'])->update(['status' => $status]);

        return $status;  // নতুন স্ট্যাটাস রিটার্ন করা হচ্ছে
    }

    public function deleteSubadmin($id)
    {
        Admin::where('id',$id)->delete();
        $message ='Subadmin delete successfuly!';
        return array("message"=>$message);
    }

public function addEditSubadmin($request) {
    $data = $request->all();

    if (isset($data['id']) && $data['id'] != "") {
        $subadmindata = Admin::find($data['id']);
        $message = "Subadmin update successfuly!";
    } else {
        $subadmindata = new Admin;
        $message = "Suadmin added successfuly!";
    }

    // upload image
    if ($request->hasFile('image')) {
        $image_tmp = $request->file('image');
        if ($image_tmp->isValid()) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image_tmp);

            $extension = $image_tmp->getClientOriginalExtension();
            $imageName = rand(111, 99999) . '.' . $extension;
            $image_path = 'admin/images/photos/' . $imageName;
            $image->save($image_path);
        }
    } else if (!empty($data['current_image'])) {
        $imageName = $data['current_image'];
    } else {
        $imageName = "";
    }

    $subadmindata->image = $imageName;
    $subadmindata->name = $data['name'];
    $subadmindata->mobile = $data['mobile'];

    if (!isset($data['id'])) {
        $subadmindata->email = $data['email'];
        $subadmindata->role = 'subadmin';
        $subadmindata->status = 1;
    }

    if ($data['password'] != "") {
        $subadmindata->password = bcrypt($data['password']);
    }

    $subadmindata->save();

    return array('message' => $message);
}

   //updateRole
   public function updateRole($request)
   {
    $data = $request->all();
      //Remove roles
      AdminsRole::where('subadmin_id',$data['subadmin_id'])->delete();

      //assign new role
      foreach($data as $key=>$value){
        if(!is_array($value)) continue;
        $view =isset($value['view']) ? $value['view']:0;
        $edit = isset($value['edit']) ? $value['edit']:0;
        $full = isset($value['full']) ? $value['full']:0;
        AdminsRole::insert([
            'subadmin_id'=>$data['subadmin_id'],
            'model'=>$key,
            'view_access'=>$view,
            'edit_access'=>$edit,
            'full_access'=>$full
        ]);
      }
      return['message'=>'Subadmin Roles update successfuly!'];
   }
}

