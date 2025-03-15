<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReceiptController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
    Route::post('users/{user}/remove-role', [UserController::class, 'removeRole'])->name('users.remove-role');

    Route::post('users/{user}/assign-branch', [UserController::class, 'assignBranch'])->name('users.assign-branch');
    Route::post('users/{user}/remove-branch', [UserController::class, 'removeBranch'])->name('user.remove-Branch');
    
    Route::post('users/{user}/add-roles', [UserController::class, 'addRoles'])->name('users.addRoles');
    Route::post('users/{user}/remove-roles', [UserController::class, 'removeRoles'])->name('users.removeRoles');

    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/edit-data', [RoleController::class, 'editData'])->name('roles.edit-data');
    Route::resource('permissions', PermissionController::class);
    Route::post('roles/{role}/add-permissions', [RoleController::class, 'addPermissions'])->name('roles.addPermissions');
    //Route::post('roles/{role}/remove-permissions', [RoleController::class, 'removePermissions'])->name('roles.removePermissions');
    Route::post('roles/{role}/remove-permission', [RoleController::class, 'removePermission'])->name('roles.remove-permission');
    //Route::delete('/roles/{role}/remove-permission/{permission}',[RoleController::class,'removePermission'])->name('role.remove.permission');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);

    Route::resource('invoices', InvoiceController::class);
    Route::resource('receipts', ReceiptController::class);
    Route::get('receipts/create/{invoice}', [ReceiptController::class, 'create_receipt'])->name('receipts.create-receipt');
    Route::post('/invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-as-paid');

});
