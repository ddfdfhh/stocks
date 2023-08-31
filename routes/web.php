<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CrudGeneratorController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () { /*home is redirect route defined in fortservice provider after logi auth  from here divert route based on role,dont use separate admin rout files now  */
    if (auth()->user()->hasRole(['Admin'])) {
        return redirect(route('admin.dashboard'));
    }
     else if (auth()->user()->hasRole(['User'])) {
        return redirect(route('user.dashboard'));
    }

});
/** ==============Email verification customisation =========== */
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

//resend mail
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
/**===================================End custom verification============================== */
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/clear_cache', [FrontendController::class, 'clear_cache'])->name('clear_cache');
Route::get('/cache', [FrontendController::class, 'cache'])->name('cache');
Route::group(['middleware' => ['guest']], function () {
    /**
     * Register Routes
     */

    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('register.show');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.perform');

    /**
     * Login Routes
     */
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'show'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.perform');

    Route::get('forget-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ForgetPassword'])->name('ForgetPasswordGet');
    Route::post('forget-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ForgetPasswordStore'])->name('ForgetPasswordPost');
    Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ResetPassword'])->name('ResetPasswordGet');
    Route::post('reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ResetPasswordStore'])->name('ResetPasswordPost');
    Route::get('verify_email/{_vX00}/{_tX00}', [App\Http\Controllers\Auth\RegisterController::class, 'verify_email'])->name('email_verify');

});

Route::get('/admin_create', function (Request $r) {

    $user = \App\Models\User::create(['name' => 'Admin', 'email' => 'admin@admin.com', 'phone' => '1123456', 'password' => Hash::make('12345678')]);
    $user->assignRole('Admin');

});
Route::get('/redirect', [FrontendController::class, 'redirect']);
Route::post('/fieldExist', [CommonController::class, 'field_exist']);
Route::post('/getDependentSelectData', [CommonController::class, 'getDependentSelectData']);
Route::post('/getDependentSelectDataMultipleVal', [CommonController::class, 'getDependentSelectDataMultipleVal']);
Route::group(['middleware' => ['auth']], function () {

    Route::post('delete_file_from_table', [CommonController::class, 'deleteFileFromTable'])->name('deleteTableFile');
    Route::post('assignUser', [CommonController::class, 'assignUser'])->name('assignUser');
    Route::post('delete_file_self', [CommonController::class, 'deleteFileFromSelf'])->name('deleteFileSelf');
    Route::post('table_field_update', [CommonController::class, 'table_field_update'])->name('table_filed_update');
    Route::post('getTableColumn', [CommonController::class, 'getColumnsFromTable']);
    Route::post('getTableColumnCheckboxForm', [CommonController::class, 'getColumnsFromTableCheckbox']);
    Route::post('getValidationHtml', [CommonController::class, 'getValidationHtml']);
    Route::post('getRepeatableHtml', [CommonController::class, 'getRepeatableHtml']);
    Route::post('getCreateInputOptionHtml', [CommonController::class, 'getCreateInputOptionHtml']);
    Route::post('getToggableGroupHtml', [CommonController::class, 'getToggableGroupHtml']);
    // Route::get('/dashboard', [AController::class, 'index'])->name('admin.dashboard');

/******Modules Routes From Here */
});
Route::prefix('admin')->middleware(['auth', 'IsAdmin'])->group(function () {

    
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/crud', [CrudGeneratorController::class, 'index'])->name('admin.crud');

    Route::match (['get', 'post'], '/generateModule', [CrudGeneratorController::class, 'generateModule'])->name('admin.generateModule');
    Route::match (['get', 'post'], '/generateTable', [CrudGeneratorController::class, 'generateTable'])->name('admin.generateTable');
    Route::match (['get', 'post'], '/addTableRelationship', [CrudGeneratorController::class, 'addTableRelationship'])->name('admin.addTableRelationship');
/******Modules Routes From Here */

   
Route::resource('roles', 'RoleController');
Route::post('roles/view', [App\Http\Controllers\RoleController::class, 'view'])->name('roles.view');
Route::post("role/load_form", [App\Http\Controllers\RoleController::class, "loadAjaxForm"])->name("role.loadAjaxForm");
Route::get("export_roles/{type}", [App\Http\Controllers\RoleController::class, "exportRole"])->name("role.export");

    Route::resource('permissions', 'PermissionController');
    Route::post('permissions/view', [PermissionController::class, 'view'])->name('permissions.view');
    Route::post("permission/load_form", [PermissionController::class, "loadAjaxForm"])->name("permission.loadAjaxForm");
    Route::resource('users', 'UserController');
    Route::post('users/view', [UserController::class, 'view'])->name('users.view');
    Route::post("user/load_form", [UserController::class, "loadAjaxForm"])->name("user.loadAjaxForm");
    Route::get("export_users/{type}", [UserController::class, "exportUser"])->name("user.export");

/**=========================Genrate rotues from here */

    Route::resource('categories', 'CategoryController');
    Route::post('categories/view', [App\Http\Controllers\CategoryController::class, 'view'])->name('categories.view');
    Route::post("category/load_form", [App\Http\Controllers\CategoryController::class, "loadAjaxForm"])->name("category.loadAjaxForm");
    Route::get("export_categories/{type}", [App\Http\Controllers\CategoryController::class, "exportCategory"])->name("category.export");

    Route::resource('products', 'ProductController');
    Route::post('products/view', [App\Http\Controllers\ProductController::class, 'view'])->name('products.view');
    Route::get("export_products/{type}", [App\Http\Controllers\ProductController::class, "exportProduct"])->name("product.export");

});
