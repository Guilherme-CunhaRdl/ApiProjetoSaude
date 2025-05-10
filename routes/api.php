<?php

use App\Http\Controllers\RemedioController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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


//PARTE DO REMEDIO
Route::get('/remedios', [RemedioController::class, 'index']);
Route::post('/remediosRegistrar', [RemedioController::class, 'store']);
Route::delete('/remedios/{id}', [RemedioController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
