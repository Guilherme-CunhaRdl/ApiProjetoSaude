<?php

use App\Http\Controllers\RemedioController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\FrequenciaCardiacaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/registrar', [UserController::class, 'registrar']);
Route::post('/login', [UserController::class, 'login']);
Route::delete('/deletarConta', [UserController::class, 'deletarConta'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/perfil', function (Request $request) {
    $user = $request->user();
    
    return response()->json([
        'nome' => $user->nome,
        'email' => $user->email,
        'peso' => $user->peso,
        'altura' => $user->altura,
        'imagem_url' => $user->imagem_path 
        ? asset("uploads/{$user->imagem_path}")
        : asset("uploads/default/avatar.png")
    ]);
});

Route::middleware('auth:sanctum')->post('/atualizarPerfil', [UserController::class, 'atualizarPerfil']);


// PARTE DO REMEDIO
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/remedios', [RemedioController::class, 'index']);
    Route::post('/remedios', [RemedioController::class, 'insertRemedio']);
    Route::post('/remedios/{id}/update', [RemedioController::class, 'update']); // Rota específica para update
    Route::delete('/remedios/{id}', [RemedioController::class, 'destroy']);
});



//PARTE DA FREQUENCIA CARDIACA
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/frequencia-cardiaca', [FrequenciaCardiacaController::class, 'index']);
    Route::post('/frequencia-cardiaca', [FrequenciaCardiacaController::class, 'store']);
    Route::delete('/frequencia-cardiaca/{id}', [FrequenciaCardiacaController::class, 'destroy']);
});
Route::get('/media-frequencia/{user}', [App\Http\Controllers\FrequenciaCardiacaController::class, 'media']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
