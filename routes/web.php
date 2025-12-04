<?php

use Illuminate\Support\Facades\Route;

Route::get('/docs/inventory-ui', function () {
    return view('swagger'); // carga swagger.blade.php
});

    

