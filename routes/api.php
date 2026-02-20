<?php

// Juan Camilo Pulgarin - Alejandro Díaz Ruiz

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CursoController;

Route::get('/cursos', [CursoController::class, 'listarCursos']);
Route::get('/cursos/{id}', [CursoController::class, 'consultarCurso']);
Route::post('/cursos', [CursoController::class, 'insertarCurso']);
Route::put('/cursos/{id}', [CursoController::class, 'actualizarCurso']);
Route::delete('/cursos/{id}', [CursoController::class, 'eliminarCurso']);