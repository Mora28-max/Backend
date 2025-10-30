<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\ProviderController;
use App\Http\Controllers\Inventory\InventoryMaterialController;
use App\Http\Controllers\Inventory\GoodsController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\GoodsStatusController;
use App\Http\Controllers\Inventory\PersonTypeController;
use App\Http\Controllers\Inventory\StatusController;
use App\Http\Controllers\Catalogs\UnitiesController;

/*
|--------------------------------------------------------------------------
| Inventory API routes
|--------------------------------------------------------------------------
|
| Rutas agrupadas bajo el prefijo /api/inventory
| (no incluimos auth aquí porque lo vas a incluir dentro del group auth en api.php)
|
*/

Route::prefix('inventory')->name('inventory.')->group(function () {
    
    // ✅ Providers (definido manualmente)
    Route::get('providers', [ProviderController::class, 'index']);        // Listar todos
    Route::get('providers/{id}', [ProviderController::class, 'show']);    // Mostrar uno
    Route::post('providers', [ProviderController::class, 'store']);       // Crear
    Route::post('providers/{id}', [ProviderController::class, 'update']); // Actualizar
    Route::delete('providers/{id}', [ProviderController::class, 'destroy']); // Eliminar


    // Inventory Materials
     Route::get('materials', [InventoryMaterialController::class, 'index']);
    Route::get('materials/{id}', [InventoryMaterialController::class, 'show']);
    Route::post('materials', [InventoryMaterialController::class, 'store']);
    Route::post('materials/{id}', [InventoryMaterialController::class, 'update']);
    Route::delete('materials/{id}', [InventoryMaterialController::class, 'destroy']);

     // Goods (bienes)
    Route::get('goods', [GoodsController::class, 'index']);          // Listar todos
    Route::post('goods', [GoodsController::class, 'store']);         // Crear
    Route::get('goods/{id}', [GoodsController::class, 'show']);      // Mostrar uno
    Route::post('goods/{id}', [GoodsController::class, 'update']);    // Actualizar
    Route::delete('goods/{id}', [GoodsController::class, 'destroy']); // Eliminar

    // Unities (unidades)
    Route::apiResource('unities', UnitiesController::class);

    // ✅ Categories (categorías de bienes)
    Route::apiResource('categories', CategoryController::class);

    // ✅ Goods Status (estados de bienes)
    Route::apiResource('goods_status', GoodsStatusController::class);
 
    // ✅ Person Types (tipos de persona)
    Route::apiResource('person_types', PersonTypeController::class);

    // ✅ Statuses (estatus generales)
    Route::apiResource('statuses', StatusController::class);
});
