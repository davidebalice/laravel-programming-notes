<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubcategoryController;
use App\Http\Controllers\Backend\NoteController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserBackendController;
use App\Http\Controllers\User\UserController;

Route::controller(IndexController::class)->group(function(){
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });
});

Route::middleware('auth','role:admin')->group(function () {
    Route::get('admin/dashboard', [AdminController::class,'Dashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'Profile'])->name('admin.profile');
    Route::get('/admin/change/password', [AdminController::class, 'ChangePassword'])->name('admin.change.password');
    Route::get('admin/logout', [AdminController::class,'Destroy'])->name('admin.logout');

    Route::group(['middleware' => ['demo_mode']], function () {
        Route::post('/admin/profile/store', [AdminController::class, 'Store'])->name('admin.profile.store');
        Route::post('/admin/update/password', [AdminController::class, 'UpdatePassword'])->name('update.password');
    });

    Route::controller(CategoryController::class)->group(function(){
        Route::get('/admin/categories' , 'Categories')->name('categories');
        Route::get('/add/category' , 'Add')->name('add.category');
        Route::get('/edit/category/{id}' , 'Edit')->name('edit.category');

        Route::group(['middleware' => ['demo_mode']], function () {
            Route::post('/store/category' , 'Store')->name('store.category');
            Route::post('/update/category' , 'Update')->name('update.category');
            Route::post('/delete/category/{id}' , 'Delete')->name('delete.category');
            Route::post('/active/category/{id}', 'Active')->name('category.active');
            Route::get('/admin/category/sort/{action}/{id}', 'Sort')->name('admin.category.sort'); 
        });
    });

    Route::controller(SubcategoryController::class)->group(function(){
        Route::get('/admin/subcategories/{category_id?}' , 'Subcategories')->name('subcategories');
        Route::get('/add/subcategory' , 'Add')->name('add.subcategory');
        Route::get('/edit/subcategory/{id}' , 'Edit')->name('edit.subcategory');
        Route::get('/get-subcategories/{category_id}', 'getSubcategories')->name('get.subcategories');

        Route::group(['middleware' => ['demo_mode']], function () {
            Route::post('/store/subcategory' , 'Store')->name('store.subcategory');
            Route::post('/update/subcategory' , 'Update')->name('update.subcategory');
            Route::post('/delete/subcategory/{id}' , 'Delete')->name('delete.subcategory');
            Route::post('/active/subcategory/{id}', 'Active')->name('subcategory.active');
            Route::get('/admin/subcategory/sort/{action}/{id}', 'Sort')->name('admin.subcategory.sort'); 
        });
    });

    Route::controller(NoteController::class)->group(function(){
        Route::get('/notes' , 'Notes')->name('notes');
        Route::post('/notes/search', 'Notes')->name('notes.search');
        Route::get('/add/note' , 'Add')->name('add.note');
        Route::get('/view/note/{id}' , 'Detail')->name('view.note');
        Route::get('/edit/note/{id}' , 'Edit')->name('edit.note');
       
        Route::group(['middleware' => ['demo_mode']], function () {
            Route::post('/store/note' , 'Store')->name('store.note');
            Route::post('/store/note/text/' , 'StoreText')->name('store.text');
            Route::post('/update/note' , 'UpdateNote')->name('update.note');
            Route::post('/update/text' , 'UpdateText')->name('update.text');
            Route::post('/delete/note/{id}' , 'Delete')->name('delete.note');
            Route::post('/delete/text/{note_id}/{text_id}' , 'DeleteText')->name('delete.text');
            Route::post('/save/code', 'SaveCode')->name('save.code');
            Route::get('up/{note_id}/{text_id}','Up')->name('note.up');
            Route::get('down/{note_id}/{text_id}','Down')->name('note.down');
        });
    });
});

Route::get('/autocomplete.js', function () {
    return response()->file(storage_path('app/public/autocomplete.js'));
});
   
Route::middleware('auth', 'checkrole')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::group(['middleware' => ['demo_mode']], function () {
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

Route::get('/admin/login', [AdminController::class,'Login'])->middleware(RedirectIfAuthenticated::class)->name('admin.login');

Route::controller(UserBackendController::class)->group(function(){
    Route::get('/admin/users' , 'Users')->name('users');
    Route::get('/admin/edit/user/{id}' , 'Edit')->name('edit.user');

    Route::group(['middleware' => ['demo_mode']], function () {
        Route::post('/admin/update/user' , 'Update')->name('update.user');
        Route::post('/admin/delete/user/{id}' , 'Delete')->name('delete.user');
    }); 
}); 

Route::controller(UserController::class)->group(function(){
    Route::get('/user/account/page' , 'UserAccount')->name('user.account.page');
    Route::get('/user/change/password' , 'UserChangePassword')->name('user.change.password');
    Route::get('/user/order/page' , 'UserOrderPage')->name('user.order.page');
    Route::get('/user/order_details/{order_id}' , 'UserOrderDetails');
    Route::get('/user/invoice_download/{order_id}' , 'UserOrderInvoice'); 
    Route::post('/return/order/{order_id}' , 'ReturnOrder')->name('return.order'); 
    Route::get('/return/order/page' , 'ReturnOrderPage')->name('return.order.page');
    Route::get('/user/track/order' , 'UserTrackOrder')->name('user.track.order');
    Route::post('/order/tracking' , 'OrderTracking')->name('order.tracking');
}); 

Route::controller(RoleController::class)->group(function(){
    Route::get('/admin/permission' , 'Permissions')->name('permissions');
    Route::get('/add/permission' , 'Add')->name('add.permission');
    Route::get('/edit/permission/{id}' , 'Edit')->name('edit.permission');

    Route::group(['middleware' => ['demo_mode']], function () {
        Route::post('/store/permission' , 'Store')->name('store.permission');
        Route::post('/update/permission' , 'Update')->name('update.permission');
        Route::post('/delete/permission/{id}' , 'Delete')->name('delete.permission');
    });
});
   
Route::controller(RoleController::class)->group(function(){
    Route::get('/admin/roles' , 'Roles')->name('roles');
    Route::get('/add/roles' , 'AddRoles')->name('add.roles');
    Route::get('/edit/roles/{id}' , 'EditRoles')->name('edit.roles');
    Route::get('/add/roles/permission' , 'AddRolesPermission')->name('add.roles.permission');
    Route::get('/admin/roles/permission' , 'AllRolesPermission')->name('roles.permissions');
    Route::get('/admin/edit/roles/{id}' , 'AdminRolesEdit')->name('admin.edit.roles');
    
    Route::group(['middleware' => ['demo_mode']], function () {
        Route::post('/store/roles' , 'StoreRoles')->name('store.roles');
        Route::post('/update/roles' , 'UpdateRoles')->name('update.roles');
        Route::post('/delete/roles/{id}' , 'DeleteRoles')->name('delete.roles');
        Route::post('/role/permission/store' , 'RolePermissionStore')->name('role.permission.store');
        Route::post('/admin/roles/update/{id}' , 'AdminRolesUpdate')->name('admin.roles.update');
        Route::post('/admin/delete/roles/{id}' , 'AdminRolesDelete')->name('admin.delete.roles');
    });
});
   
Route::controller(AdminController::class)->group(function(){
    Route::get('/admin/admins' , 'Admin')->name('admins');
    Route::get('/add/admin' , 'AddAdmin')->name('add.admin');
    Route::get('/edit/admin/role/{id}' , 'EditAdminRole')->name('edit.admin.role');
   
    Route::group(['middleware' => ['demo_mode']], function () {
        Route::post('/admin/user/store' , 'AdminUserStore')->name('admin.user.store');
        Route::post('/admin/user/update/{id}' , 'AdminUserUpdate')->name('admin.user.update');
        Route::post('/delete/admin/role/{id}' , 'DeleteAdminRole')->name('delete.admin.role');
    });
});
   
require __DIR__.'/auth.php';