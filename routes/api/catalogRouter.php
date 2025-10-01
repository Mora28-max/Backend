<?php

use App\Models\Catalogs\UseOfType;
use Spatie\Permission\Models\Role;
use App\Models\WaterTank\WaterTank;
use App\Models\Catalogs\PaymentType;
use App\Models\Catalogs\ServiceType;
use App\Models\Catalogs\CustomerType;
use Illuminate\Support\Facades\Route;
use App\Models\Catalogs\ProcessStatus;
use App\Models\Catalogs\ReportCategory;
use App\Models\Catalogs\ReportPriority;
use App\Models\Catalogs\ClassificationType;
use App\Models\Catalogs\ClassificationOfMaterials;
use App\Http\Controllers\Catalogs\UnitiesController;
use App\Http\Controllers\Shared\GenericCatalogController;
use App\Http\Controllers\Catalogs\ServiceStatusController;
use App\Http\Controllers\Catalogs\ReportSubcategoryController;
use App\Http\Controllers\Catalogs\ExtraordinaryAccountController;
use App\Http\Controllers\Catalogs\AdditionalObservationsController;
use App\Http\Controllers\Catalogs\ReportChildSubcategoryController;

Route::get('/roles', function () {
    return (new GenericCatalogController(Role::class))->index();
});
Route::get('/report-categories', function () {
    return (new GenericCatalogController(ReportCategory::class))->index();
});
Route::get('/classification-types', function () {
    return (new GenericCatalogController(ClassificationType::class))->index();
});
Route::get('/customer-types', function () {
    return (new GenericCatalogController(CustomerType::class))->index();
});
Route::get('/service-types', function () {
    return (new GenericCatalogController(ServiceType::class))->index();
});
Route::get('/use-of-types', function () {
    return (new GenericCatalogController(UseOfType::class))->index();
});
Route::get('/classification-of-materials', function () {
    return (new GenericCatalogController(ClassificationOfMaterials::class))->index();
});
Route::get('/report-priorities', function () {
    return (new GenericCatalogController(ReportPriority::class))->index();
});
Route::get('/process-status', function () {
    return (new GenericCatalogController(ProcessStatus::class))->index();
});
Route::get('/payment-types', function () {
    return (new GenericCatalogController(PaymentType::class))->index();
});
Route::get('/water-tanks', function () {
    return (new GenericCatalogController(WaterTank::class))->index();
});
Route::get('/reports-subcategories', [ReportSubcategoryController::class, 'index']);
Route::get('/reports-child-subcategories', [ReportChildSubcategoryController::class, 'index']);
Route::get('/service-status', [ServiceStatusController::class, 'index']);
Route::get('/unities', [UnitiesController::class, 'index']);

Route::apiResource('/additional-observations', AdditionalObservationsController::class);
Route::apiResource('/extraordinary-accounts', ExtraordinaryAccountController::class)->except(['show', 'update']);
