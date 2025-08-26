<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DetailRequest;
use App\Models\Admin;
use App\Models\AdminsRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Admin\LoginRequest;
use App\Services\Admin\AdminServices;
use App\Http\Requests\Admin\PasswordRequest;
use App\Http\Requests\Admin\SubadminRequest;
use App\Models\ColumnPreference;

class AdminController extends Controller
{
    protected $adminServices;
    public function __construct(AdminServices $adminServices)
    {
        $this->adminServices = $adminServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Session::put('page', 'dashboard');
        $categoriesCount = \App\Models\Category::get()->count();
        $productsCount = \App\Models\Product::get()->count();
        $brandsCount = \App\Models\Brand::get()->count();
        $usersCount = \App\Models\User::get()->count();
        $couponsCount = 0;
        $ordersCount = 0;
        $pagesCount = 0;
        $enquiriesCount = 0;
        return view('admin.dashboard')->with(compact(
            'categoriesCount',
            'productsCount',
            'brandsCount',
            'usersCount',
            'couponsCount',
            'ordersCount',
            'pagesCount',
            'enquiriesCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoginRequest $request)
    {
        $data = $request->all();
        $loginStatus = $this->adminServices->login($data);
        if ($loginStatus == "success") {
            return redirect()->route('dashboard.index');
        } elseif ($loginStatus == "inactive") {
            return redirect()->back()->with('error_message', 'Your account is inactive. Please contact the administrator');
        } else {
            return redirect()->back()->with('error_message', 'Invalid Email or Password!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        Session::put('page', 'update-password');
        return view('admin.update_password');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        Auth::guard('admin')->logout();
        return redirect()->back()->with('admin.login');
    }

    public function verifyPassword(Request $request)
    {
        $data = $request->all();
        return $this->adminServices->verifyPassword($data);
    }

    public function updatePasswordRequest(PasswordRequest $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();
            $pwdStatus = $this->adminServices->updatePassword($data);
            if ($pwdStatus['status'] == 'success') {
                return redirect()->back()->with('success_message', $pwdStatus['message']);
            } else {
                return redirect()->back()->with('error_message', $pwdStatus['message']);
            }
        }

    }
    public function editDetails()
    {
        Session::put('page', 'update-details');
        return view('admin.update_details');
    }
    public function updateDetails(DetailRequest $request)
    {
        Session::put('page', 'update-details');

        if ($request->isMethod('post')) {
            $this->adminServices->updateDetails($request);
            return redirect()->back()->with('success_message', 'Admin Details have been updated successfully');
        }


    }
    public function deleteProfileImage(Request $request)
    {
        $status = $this->adminServices->deleteProfileImage($request->admin_id);
        return response()->json($status);
    }

    public function subadmins()
    {
        Session::put('page', 'subadmins');
        $subadmins = $this->adminServices->subadmins();
        return view('admin.subadmins.subadmins', compact('subadmins'));
    }

    public function updateSubadminStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            // AdminServices মেথড কল করে স্ট্যাটাস আপডেট করা
            $status = $this->adminServices->updateSubadminStatus($data);

            return response()->json(['status' => $status, 'subadmin_id' => $data['subadmin_id']]);
        }
    }

    public function deleteSubadmin($id)
    {
        $result = $this->adminServices->deleteSubadmin($id);
        return redirect()->back()->with('success_message', $result['message']);
    }

    public function addEditSubadmin($id = null)
    {
        if ($id == "") {
            $title = "Add Subadmin";
            $subadmindata = array();
        } else {
            $title = "Edit Subadmin";
            $subadmindata = Admin::find($id);
        }
        return view('admin.subadmins.add_edit_subadmin')->with(compact('title', 'subadmindata'));
    }

    public function addEditSubadminRequest(SubadminRequest $request)
    {
        if ($request->isMethod('post')) {
            $result = $this->adminServices->addEditSubadmin($request);
            return redirect('admin/subadmins')->with('success_message', $result['message']);
        }
    }

    public function updateRole($id)
    {
        $subadminRoles = AdminsRole::where('subadmin_id', $id)->get()->toArray();
        $subadminDetails = Admin::where('id', $id)->first()->toArray();
        $modules = ['categories', 'products', 'orders', 'users', 'report', 'brands'];
        $title = "Update " . $subadminDetails['name'] . " Subadmin Roles/Permission";

        return view('admin.subadmins.update_roles')
            ->with(compact('title', 'id', 'subadminRoles', 'modules'));
    }

    public function updateRoleRequest(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $service = new AdminServices();
            $result = $service->updateRole($request);
            return redirect()->back()->with('success_message', $result['message']);
        }
    }

    // Save column order (used when columns are reordered)
    public function saveColumnOrder(Request $request)
    {
        $userId = Auth::guard('admin')->id();
        $tableName = $request->table_key;

        if (!$tableName) {
            return response()->json(['status' => 'error', 'message' => 'Table key is required'], 400);
        }

        // Update or create the column order preference
        ColumnPreference::updateOrCreate(
            ['admin_id' => $userId, 'table_name' => $tableName],
            ['column_order' => json_encode($request->column_order)] // Save the column order
        );

        return response()->json(['status' => 'success']);
    }

    // Save column visibility (used when columns are hidden/shown)
    public function saveColumnVisibility(Request $request)
    {
        $userId = Auth::guard('admin')->id();
        $tableName = $request->table_key;

        if (!$tableName) {
            return response()->json(['status' => 'error', 'message' => 'Table key is required'], 400);
        }

        // Save or update column preferences
        ColumnPreference::updateOrCreate(
            ['admin_id' => $userId, 'table_name' => $tableName],
            [
                'column_order' => json_encode($request->column_order),
                'hidden_columns' => json_encode($request->hidden_columns)
            ]
        );

        return response()->json(['status' => 'success']);
    }


}
