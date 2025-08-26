<?php /** @noinspection ALL */

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\FilterValueController;
use App\Http\Controllers\Admin\FilterController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Front\ProductController as FrontProductController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('admin')->group(function () {
    //Show login form
    Route::get('login', [AdminController::class, 'create'])->name('admin.login');
    Route::post('login', [AdminController::class, 'store'])->name('admin.login.request');

    Route::group(['middleware' => ['admin']], function () {
        Route::resource('dashboard', AdminController::class)->only(['index']);
        Route::get('logout', [AdminController::class, 'destroy'])->name('admin.logout');
        Route::get('update-password', [AdminController::class, 'edit'])->name('admin.update-password');
        Route::post('verify-password', [AdminController::class, 'verifyPassword'])->name('admin.verify.passowrd');
        Route::post('admin/update-password', [AdminController::class, 'updatePasswordRequest'])->name('admin.update-password.request');
        Route::get('update-details', [AdminController::class, 'editDetails'])->name('admin.update-details');
        Route::post('update-details', [AdminController::class, 'updateDetails'])->name('admin.update-details.request');
        Route::post('delete-profile-image', [AdminController::class, 'deleteProfileImage']);
        //Subadmins
        Route::get('subadmins', [AdminController::class, 'subadmins']);
        Route::post('update-subadmin-status', [AdminController::class, 'updateSubadminStatus']);
        Route::get('delete-subadmin/{id}', [AdminController::class, 'deleteSubadmin']);
        Route::get('add-edit-subadmin/{id?}', [AdminController::class, 'addEditSubadmin']);
        Route::post('add-edit-subadmin/request', [AdminController::class, 'addEditSubadminRequest']);
        //Roles
        Route::get('/update-role/{id}', [AdminController::class, 'updateRole']);
        Route::post('/update-role/request', [AdminController::class, 'updateRoleRequest']);
        //Category
        Route::resource('categories', CategoryController::class);
        Route::post('update-category-status', [CategoryController::class, 'updateCategoryStatus']);
        Route::post('delete-category-image', [CategoryController::class, 'deleteCategoryImage']);
        ///Product route
        Route::resource('product', ProductController::class);
        Route::post('update-product-status', [ProductController::class, 'updateProductStatus']);
        Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload.image');
        Route::post('/products/upload-images', [ProductController::class, 'uploadImages'])->name('products.upload.images');
        Route::post('/products/save-images/{productId}', [ProductController::class, 'saveProductImages'])->name('products.save.images');
        Route::post('/product/delete-image', [ProductController::class, 'deleteImage'])->name('products.delete.image');
        Route::post('/products/upload-video', [ProductController::class, 'uploadProductVideo'])->name('products.upload.video');
        Route::post('/products/delete-main-image', [ProductController::class, 'deleteProductMainImage'])->name('products.delete.main.image');
        Route::get('delete-product-attribute/{id}', [ProductController::class, 'deleteProductAttribut']);
        // Route::post('/save-column-order',[AdminController::class,'saveColumnOrder']);
        Route::post('/save-column-visibility', [AdminController::class, 'saveColumnVisibility']);
        // Route::post('/save-column-order', [AdminController::class, 'saveColumnOrder']);
///Brand
        Route::resource('brand', BrandController::class);
        Route::post('update-brand-status', [BrandController::class, 'updateBrandStatus']);
        Route::resource('banner', BannerController::class);
        Route::post('update-banner-status', [BannerController::class, 'updateBannerStatus']);
        Route::get('delete-banner/{id}', [BannerController::class, 'deleteBanner']);
        ///Filters
        Route::resource('filters', FilterController::class);
        Route::post('update-filter-status', [FilterController::class, 'updateFilterStatus'])->name('filters.updateStatus');
        //filtersvalues
        Route::prefix('filters/{filter}')->group(function () {
            Route::resource('filter-values', FilterValueController::class)
                ->parameter('filter-values', 'value');
        });



    });
});

Route::
        namespace('App\Http\Controllers\Front')->group(function () {
            Route::get('/', [IndexController::class, 'index']);

    $caturls = Category::where('status', 1)->pluck('url')->toArray();
    foreach ($caturls as $url) {
        Route::get("/$url", [FrontProductController::class, 'index']);
    }
        });
