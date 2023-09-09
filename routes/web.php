<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CrudGeneratorController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () { /*home is redirect route defined in fortservice provider after logi auth  from here divert route based on role,dont use separate admin rout files now  */
    if (auth()->user()->hasRole(['Admin'])) {
        return redirect(route('admin.dashboard'));
    } else if (auth()->user()->hasRole(['User'])) {
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
    Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('auth.logout');
    Route::post('/getUnitByMeterialId', [App\Http\Controllers\CommonController::class, 'getUnitByMeterialId']);

    Route::post('delete_file_from_table', [CommonController::class, 'deleteFileFromTable'])->name('deleteTableFile');
    Route::post('deleteInJsonColumnData', [CommonController::class, 'deleteInJsonColumnData'])->name('deleteInJsonColumnData');
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
    Route::get('/dashboard_data', [AdminController::class, 'dashboard_data'])->name('admin.dashboard_data');
    Route::get('/unauthorized', [AdminController::class, 'unauthorized'])->name('admin.unauthorized');
    Route::get('/crud', [CrudGeneratorController::class, 'index'])->name('admin.crud');

    Route::match(['get', 'post'], '/generateModule', [CrudGeneratorController::class, 'generateModule'])->name('admin.generateModule');
    Route::match(['get', 'post'], '/generateTable', [CrudGeneratorController::class, 'generateTable'])->name('admin.generateTable');
    Route::match(['get', 'post'], '/addTableRelationship', [CrudGeneratorController::class, 'addTableRelationship'])->name('admin.addTableRelationship');
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
    Route::post("addEditRemark", [App\Http\Controllers\LeadsController::class, "addEditRemark"])->name("addEditRemark");

/**=========================Genrate rotues from here */

    Route::resource('categories', 'CategoryController');
    Route::post('categories/view', [App\Http\Controllers\CategoryController::class, 'view'])->name('categories.view');
    Route::post("category/load_form", [App\Http\Controllers\CategoryController::class, "loadAjaxForm"])->name("category.loadAjaxForm");
    Route::get("export_categories/{type}", [App\Http\Controllers\CategoryController::class, "exportCategory"])->name("category.export");

    Route::resource('products', 'ProductController');
    Route::post('products/view', [App\Http\Controllers\ProductController::class, 'view'])->name('products.view');
    Route::post("product/load_form", [App\Http\Controllers\ProductController::class, "loadAjaxForm"])->name("product.loadAjaxForm");
    Route::get("export_products/{type}", [App\Http\Controllers\ProductController::class, "exportProduct"])->name("product.export");

    Route::resource('states', 'StateController');
    Route::post('states/view', [App\Http\Controllers\StateController::class, 'view'])->name('states.view');
    Route::post("state/load_form", [App\Http\Controllers\StateController::class, "loadAjaxForm"])->name("state.loadAjaxForm");
    Route::get("export_states/{type}", [App\Http\Controllers\StateController::class, "exportState"])->name("state.export");

    Route::resource('cities', 'CityController');
    Route::post('cities/view', [App\Http\Controllers\CityController::class, 'view'])->name('cities.view');
    Route::post("city/load_form", [App\Http\Controllers\CityController::class, "loadAjaxForm"])->name("city.loadAjaxForm");
    Route::get("export_cities/{type}", [App\Http\Controllers\CityController::class, "exportCity"])->name("city.export");

    Route::resource('customers', 'CustomerController');
    Route::post('customers/view', [App\Http\Controllers\CustomerController::class, 'view'])->name('customers.view');
    Route::get("export_customers/{type}", [App\Http\Controllers\CustomerController::class, "exportCustomer"])->name("customer.export");

    Route::resource('suppliers', 'SupplierController');
    Route::post('suppliers/view', [App\Http\Controllers\SupplierController::class, 'view'])->name('suppliers.view');
    Route::get("export_suppliers/{type}", [App\Http\Controllers\SupplierController::class, "exportSupplier"])->name("supplier.export");

    Route::resource('settings', 'SettingController');
    Route::post('settings/view', [App\Http\Controllers\SettingController::class, 'view'])->name('settings.view');
    Route::post("setting/load_form", [App\Http\Controllers\SettingController::class, "loadAjaxForm"])->name("setting.loadAjaxForm");
    Route::get("export_settings/{type}", [App\Http\Controllers\SettingController::class, "exportSetting"])->name("setting.export");

    Route::resource('demo_tables', 'DemoTableController');
    Route::post('demo_tables/view', [App\Http\Controllers\DemoTableController::class, 'view'])->name('demotables.view');
    Route::post('demo_tables/view', [App\Http\Controllers\DemoTableController::class, 'view'])->name('demotables.view');
    Route::post('demotable/load_snippets', [App\Http\Controllers\DemoTableController::class, 'load_toggle'])->name('demotables.load_toggle');
    Route::post("demotable/load_form", [App\Http\Controllers\DemoTableController::class, "loadAjaxForm"])->name("demotable.loadAjaxForm");
    Route::get("export_demotables/{type}", [App\Http\Controllers\DemoTableController::class, "exportDemoTable"])->name("demotable.export");

    Route::resource('vehicles', 'VehicleController');
    Route::post('vehicles/view', [App\Http\Controllers\VehicleController::class, 'view'])->name('vehicles.view');
    Route::post("vehicle/load_form", [App\Http\Controllers\VehicleController::class, "loadAjaxForm"])->name("vehicle.loadAjaxForm");
    Route::get("export_vehicles/{type}", [App\Http\Controllers\VehicleController::class, "exportVehicle"])->name("vehicle.export");

    Route::resource('drivers', 'DriverController');
    Route::post('drivers/view', [App\Http\Controllers\DriverController::class, 'view'])->name('drivers.view');
    Route::post("driver/load_form", [App\Http\Controllers\DriverController::class, "loadAjaxForm"])->name("driver.loadAjaxForm");
    Route::get("export_drivers/{type}", [App\Http\Controllers\DriverController::class, "exportDriver"])->name("driver.export");

    Route::resource('input_materials', 'InputMaterialController');
    Route::post('input_materials/view', [App\Http\Controllers\InputMaterialController::class, 'view'])->name('inputmaterials.view');
    Route::post("inputmaterial/load_form", [App\Http\Controllers\InputMaterialController::class, "loadAjaxForm"])->name("inputmaterial.loadAjaxForm");
    Route::get("export_inputmaterials/{type}", [App\Http\Controllers\InputMaterialController::class, "exportInputMaterial"])->name("inputmaterial.export");

    Route::resource('units', 'UnitController');
    Route::post('units/view', [App\Http\Controllers\UnitController::class, 'view'])->name('units.view');
    Route::post("unit/load_form", [App\Http\Controllers\UnitController::class, "loadAjaxForm"])->name("unit.loadAjaxForm");
    Route::get("export_units/{type}", [App\Http\Controllers\UnitController::class, "exportUnit"])->name("unit.export");

    Route::resource('create_material_stocks', 'CreateMaterialStockController');
    Route::post('create_material_stocks/view', [App\Http\Controllers\CreateMaterialStockController::class, 'view'])->name('creatematerialstocks.view');
    Route::post("creatematerialstock/load_form", [App\Http\Controllers\CreateMaterialStockController::class, "loadAjaxForm"])->name("creatematerialstock.loadAjaxForm");
    Route::get("export_creatematerialstocks/{type}", [App\Http\Controllers\CreateMaterialStockController::class, "exportCreateMaterialStock"])->name("creatematerialstock.export");

    Route::resource('generated_product_stocks', 'GeneratedProductStockController');
    Route::post('generated_product_stocks/view', [App\Http\Controllers\GeneratedProductStockController::class, 'view'])->name('generatedproductstocks.view');
    Route::get("export_generatedproductstocks/{type}", [App\Http\Controllers\GeneratedProductStockController::class, "exportGeneratedProductStock"])->name("generatedproductstock.export");

    Route::resource('create_orders', 'CreateOrderController');
    Route::post('create_orders/view', [App\Http\Controllers\CreateOrderController::class, 'view'])->name('createorders.view');
    Route::post('getOrderTotalAmount', [App\Http\Controllers\CreateOrderController::class, 'getOrderTotalAmount'])->name('createorders.getOrderTotalAmount');
    Route::get('generate_invoice/{id}', [App\Http\Controllers\CreateOrderController::class, 'generateInvoice'])->name('createorders.generateInvoice');
    Route::get("export_createorders/{type}", [App\Http\Controllers\CreateOrderController::class, "exportCreateOrder"])->name("createorder.export");

    Route::resource('lead_sources', 'LeadSourceController');
    Route::post('lead_sources/view', [App\Http\Controllers\LeadSourceController::class, 'view'])->name('leadsources.view');
    Route::post("leadsource/load_form", [App\Http\Controllers\LeadSourceController::class, "loadAjaxForm"])->name("leadsource.loadAjaxForm");
    Route::get("export_leadsources/{type}", [App\Http\Controllers\LeadSourceController::class, "exportLeadSource"])->name("leadsource.export");

    Route::resource('leads', 'LeadsController');
    Route::post('leads/view', [App\Http\Controllers\LeadsController::class, 'view'])->name('leads.view');
    Route::get("export_leads/{type}", [App\Http\Controllers\LeadsController::class, "exportLeads"])->name("leads.export");

    Route::resource('spendable_items', 'ExpsnseItemController');
    Route::post('spendable_items/view', [App\Http\Controllers\ExpsnseItemController::class, 'view'])->name('spendableitems.view');
    Route::post("expsnseitem/load_form", [App\Http\Controllers\ExpsnseItemController::class, "loadAjaxForm"])->name("expsnseitem.loadAjaxForm");
    Route::get("export_expsnseitems/{type}", [App\Http\Controllers\ExpsnseItemController::class, "exportExpsnseItem"])->name("expsnseitem.export");
    Route::resource('expenses', 'ExpenseController');
    Route::post('expenses/view', [App\Http\Controllers\ExpenseController::class,'view'])->name('expenses.view');
    Route::post("expense/load_form", [App\Http\Controllers\ExpenseController::class,"loadAjaxForm"])->name("expense.loadAjaxForm");
    Route::get("export_expenses/{type}", [App\Http\Controllers\ExpenseController::class,"exportExpense"])->name("expense.export");

    

Route::resource('receive_payments', 'ReceivePaymentController');
Route::post('receive_payments/view', [App\Http\Controllers\ReceivePaymentController::class,'view'])->name('receivepayments.view');
Route::get("export_receivepayments/{type}", [App\Http\Controllers\ReceivePaymentController::class,"exportReceivePayment"])->name("receivepayment.export");
});
