<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EanScanApiController;
use App\Http\Controllers\Api\ProdutoApiController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\Api\FuncionarioApiAuthController;

Route::post('/registrar-eans', [EanScanApiController::class, 'store']);
Route::delete('/limpar-eans', [EanScanApiController::class, 'limpar']);
Route::get('/escaneados', [ProdutoApiController::class, 'index']);
Route::get('/eans', [ProdutoController::class, 'apiListarEans']);
Route::get('/produtos-escaneados', function () {
    $codigos = \App\Models\EanScan::pluck('codigo_ean');
    $produtos = \App\Models\Produto::whereIn('codigo_ean', $codigos)->get();

    return response()->json($produtos);
});
Route::get('/produto/{codigoEAN}', [ProdutoController::class, 'detalhesProduto']);
Route::post('/login', [FuncionarioApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [FuncionarioApiAuthController::class, 'logout']);
    Route::get('/me', [FuncionarioApiAuthController::class, 'me']);
    Route::post('/produtos', [ProdutoApiController::class, 'store']);
    Route::put('produtos/{codigo_ean}', [ProdutoApiController::class, 'update']);
    Route::delete('produtos/{codigo_ean}', [ProdutoApiController::class, 'destroy']);
    Route::get('/produtos/ean/{codigo_ean}', [ProdutoApiController::class, 'showByEan']);
});