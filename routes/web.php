<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\AvailableRawMaterialController;
use App\Http\Controllers\ConsumableRawMaterialController;
use App\Http\Controllers\CastingRecordController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\vendorController;
use App\Http\Controllers\InwardingController;
use App\Http\Controllers\MaterialIssuedController;
use App\Http\Controllers\trimingController;     
use App\Http\Controllers\InspectionController;     
use App\Http\Controllers\ToolsController;     

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/admin-register', [AdminAuthController::class, 'index']);


Route::get('/login', [AdminAuthController::class, 'adminLogin']);
Route::post('/login', [AdminAuthController::class, 'login'])->name('login');

Route::post('/register', [AdminAuthController::class, 'register']);



Route::middleware(['admin'])->group(function () {
    Route::get('/', [AdminAuthController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/add-employee', [EmployeeController::class, 'index'])->name('employee.create');
    Route::post('/employee', [EmployeeController::class, 'store'])->name('employee.store');





    Route::get('/customer', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('/customer-store', [CompanyController::class, 'store'])->name('company.store');
    Route::get('/customer/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::patch('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');



    Route::get('/component', [ToolController::class, 'index'])->name('components.index');
    Route::post('/component-store', [ToolController::class, 'store'])->name('components.store');
    Route::get('/component/{id}/edit', [ToolController::class, 'edit'])->name('components.edit');
    Route::patch('/component/{id}', [ToolController::class, 'update'])->name('components.update');
    Route::delete('/component/{id}', [ToolController::class, 'destroy'])->name('components.destroy');
    Route::delete('/dispatch/{id}', [DispatchController::class, 'destroy'])->name('dispatch.destroy');





    Route::get('/tools', [ToolsController::class, 'create'])->name('toools.index');

    Route::post('/add-tools', [ToolsController::class, 'store'])->name('dispatches.store');
      Route::patch('/dispatches/{id}', [ToolsController::class, 'update'])->name('dispatches.update');

Route::get('/dispatches/{id}/edit', [ToolsController::class, 'edit'])->name('dispatches.edit');

        Route::delete('/tools/{id}', [ToolsController::class, 'destroy'])->name('dispatches.destroy');


    Route::get('/store-list', [EmployeeController::class, 'StoreList'])->name('store.list');
    Route::get('/production-list', [EmployeeController::class, 'ProductionList'])->name('production.list');
    Route::get('/inspection-list', [EmployeeController::class, 'InspectionList'])->name('inspection.list');
    Route::get('/admin-list', [EmployeeController::class, 'AdminList'])->name('admin.list');
    Route::post('/employee/toggle-status/{id}', [EmployeeController::class, 'toggleStatus'])->name('employee.toggleStatus');


    // Common controller function handle kare
    Route::get('/admin/{id}/edit', [EmployeeController::class, 'editEmployee'])->name('admin.edit');
    Route::get('/store/{id}/edit', [EmployeeController::class, 'editEmployee'])->name('store.edit');
    Route::get('/production/{id}/edit', [EmployeeController::class, 'editEmployee'])->name('production.edit');
    Route::get('/inspection/{id}/edit', [EmployeeController::class, 'editEmployee'])->name('inspection.edit');

    // Update routes (optional: can be separate too)
    Route::put('/admin/{id}', [EmployeeController::class, 'update'])->name('admin.update');
    Route::put('/store/{id}', [EmployeeController::class, 'update'])->name('store.update');
    Route::put('/production/{id}', [EmployeeController::class, 'update'])->name('production.update');
    Route::put('/inspection/{id}', [EmployeeController::class, 'update'])->name('inspection.update');

    Route::post('/employee/activate/{id}', [EmployeeController::class, 'activate'])->name('employee.activate');
    Route::post('/employee/deactivate/{id}', [EmployeeController::class, 'deactivate'])->name('employee.deactivate');




    Route::get('/admin/{id}', [EmployeeController::class, 'show'])->name('admin.show');
    Route::get('/store/{id}', [EmployeeController::class, 'show'])->name('store.show');
    Route::get('/production/{id}', [EmployeeController::class, 'show'])->name('production.show');
    Route::get('/inspection/{id}', [EmployeeController::class, 'show'])->name('inspection.show');




    Route::get('/employee/{id}/edit', [EmployeeController::class, 'editEmployee'])->name('add-employee.edit'); // Edit form

    Route::put('/employee/{id}', [EmployeeController::class, 'update'])->name('add-employee.update'); // Update employee
    Route::delete('/admin/{id}', [EmployeeController::class, 'adminDestroy'])->name('add-admin.destroy'); // Delete admin
Route::delete('/add-inspection/{id}', [EmployeeController::class, 'inspectionDestroy'])->name('add-inspection.destroy');

    Route::delete('/production/{id}', [EmployeeController::class, 'productionDestroy'])->name('add-production.destroy'); // Delete operator
    Route::delete('/store/{id}', [EmployeeController::class, 'storeDestroy'])->name('add-store.destroy'); // Delete operator
    Route::get('/operator-my-profile', [EmployeeController::class, 'myProfile'])->name('profile.view');
    Route::get('/admin-profile', [AdminAuthController::class, 'myProfile'])->name('adminProfile.view');
    Route::post('/my-profile/update', [EmployeeController::class, 'updateMyProfile'])->name('profile.update');
    Route::post('/employee/change-password', [EmployeeController::class, 'changePassword'])->name('employee.changePassword');
    Route::delete('/employee/delete/{id}', [EmployeeController::class, 'employeeDestroy'])->name('employee.delete');
    Route::delete('/admin/logout', [EmployeeController::class, 'employeeDestroy'])->name('admin.logout');
    Route::post('/admin/change-password', [AdminAuthController::class, 'changePassword'])->name('admin.changePassword');




Route::get('/tools-by-company/{company_id}', [CastingRecordController::class, 'getByCompany']);

    Route::get('/raw-material', [RawMaterialController::class, 'index'])->name('raw_material.index');
    Route::get('/available-raw-materials', [InwardingController::class, 'AvailableRawMaterials'])->name('avilable_raw_material.index');
Route::delete('/casting/process-record/delete/{id}', [CastingRecordController::class, 'destroyProcessRecord'])->name('casting.process.delete');

    Route::post('/raw-material-type', [RawMaterialController::class, 'store'])->name('raw_material.store');
    Route::get('/raw-material/{id}/edit', [RawMaterialController::class, 'edit'])->name('raw_material.edit');
    Route::post('/raw-material-type/{id}/update', [RawMaterialController::class, 'update'])->name('raw_material.update');
    Route::delete('/raw-material-type/{id}', [RawMaterialController::class, 'destroy'])->name('raw_material.destroy');
    Route::get('/available-raw-material-type', [AvailableRawMaterialController::class, 'index'])->name('available.index');
    Route::post('/available', [AvailableRawMaterialController::class, 'store'])->name('available.store');
    Route::get('/available-raw-material-type/{id}/edit', [AvailableRawMaterialController::class, 'edit'])->name('available.edit');
    Route::put('/available/{id}', [AvailableRawMaterialController::class, 'update'])->name('available.update');
    Route::delete('/available/{id}', [AvailableRawMaterialController::class, 'destroy'])->name('available.destroy');
    Route::get('/consumable-raw-material', [ConsumableRawMaterialController::class, 'index'])->name('consumable.index');
    Route::post('/consumable', [ConsumableRawMaterialController::class, 'store'])->name('consumable.store');
    Route::get('/consumable-raw-material-type/{id}/edit', [ConsumableRawMaterialController::class, 'edit'])->name('consumable.edit');
    Route::put('/consumable/{id}', [ConsumableRawMaterialController::class, 'update'])->name('consumable.update');
    Route::delete('/consumable/{id}', [ConsumableRawMaterialController::class, 'destroy'])->name('consumable.destroy');

Route::post('/production/trimming/store', [trimingController::class, 'store'])->name('trimming.store');
    Route::post('/casting/update-inspection', [InspectionController::class, 'store'])->name('casting.updateInspection');
Route::delete('/trimming/{id}', [trimingController::class, 'destroy'])->name('trimming.destroy');

    Route::get("/inspection", [InspectionController::class, 'index'])->name("inspection.index");
    Route::get("/dispatch", [DispatchController::class, 'index'])->name("dispatch.index");
    Route::post('/casting/update-dispatch', [DispatchController::class, 'store'])->name('casting.updateDispatch');

    Route::get("/triming", [trimingController::class, 'index'])->name("treeming.index");
    Route::get('/casting/treem/{id}', [CastingRecordController::class, 'TreemEdit'])->name('casting.treem.edit');
Route::get('/get-casting-quantity/{toolId}', [trimingController::class, 'getQuantity']);

    Route::get('/casting', [CastingRecordController::class, 'index'])->name('casting.index');
    Route::post('/casting', [CastingRecordController::class, 'store'])->name('casting.store');
    Route::delete('/casting/{id}', [CastingRecordController::class, 'destroy'])->name('casting.destroy');
    Route::delete('/inspection/{id}', [InspectionController::class, 'destroy'])->name('inspection.destroy');


Route::get('/api/get-trimming-qty/{toolId}', function($toolId) {
    $trimming = \App\Models\Triming::where('tool_type_id', $toolId)->first();
    return response()->json([
        'quantity_pcs' => $trimming->quantity_pcs ?? 0
    ]);
});


    Route::get('/vendor', [vendorController::class, 'index'])->name('vendor.index');
    Route::post('/vendor', [vendorController::class, 'store'])->name('vendor.store');
    Route::get('/vendor/{id}/edit', [vendorController::class, 'edit'])->name('vendor.edit');
    Route::post('/vendor/{id}/update', [vendorController::class, 'update'])->name('vendor.update');
    Route::delete('/vendor/{id}', [vendorController::class, 'destroy'])->name('vendor.destroy');


    Route::get('/in-warding', [InwardingController::class, 'index'])->name('inwardings.index');
    Route::post('/inwarding', [InwardingController::class, 'store'])->name('inwardings.store');


    Route::get('/material-issued/{id}/edit', [MaterialIssuedController::class, 'edit'])->name('material-issued.edit');
    Route::get('/inwardings/{id}/edit', [InwardingController::class, 'edit'])->name('inwardings.edit');
    Route::put('/inwardings/{id}', [InwardingController::class, 'update'])->name('inwardings.update');
    Route::delete('/inwardings/{id}', [InwardingController::class, 'destroy'])->name('inwardings.destroy');

    Route::get('/material-issued', [MaterialIssuedController::class, 'index'])->name('material-issued.index');
    Route::post('/material-issued', [MaterialIssuedController::class, 'store'])->name('material-issued.store');
    Route::delete('/material-issued/{id}', [MaterialIssuedController::class, 'destroy'])->name('material-issued.destroy');
    Route::put('/material-issued/{id}', [MaterialIssuedController::class, 'update'])->name('material-issued.update');

});
