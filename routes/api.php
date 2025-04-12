<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ContactoController;

// Rutas para listar y mostrar user
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para mostrar una lista de contactos
    Route::get('/list', [ContactoController::class, 'list']);

    // Ruta para mostrar un contacto específico
    Route::get('/detail/{id}', [ContactoController::class, 'detail']);

    // Ruta para guardar un nuevo contacto o actualizar uno existente
    Route::post('/saveOrUpdate', [ContactoController::class, 'saveOrUpdate']);

    // Ruta para eliminar un contacto
    Route::delete('/delete/{id}', [ContactoController::class, 'destroy']);
});

Route::post('/register', [ApiController::class, 'register']);
Route::post('/login', [ApiController::class, 'login']);
