<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\FuncionarioAuthController;
use App\Http\Controllers\CadastroProdutoController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Login do funcionário
Route::get('/funcionario/login', [FuncionarioAuthController::class, 'showLoginForm'])->name('login');
Route::post('/funcionario/login', [FuncionarioAuthController::class, 'login'])->name('funcionario.login');
Route::post('/funcionario/logout', [FuncionarioAuthController::class, 'logout'])->name('funcionario.logout');
Route::get('/funcionario/login', [FuncionarioAuthController::class, 'showLoginForm'])->name('funcionario.login.form');
Route::get('/login', fn() => redirect()->route('funcionario.login.form'))->name('login'); 
// Telas que so aceita autenticado
Route::middleware('auth:funcionario')->group(function () {
    // Telas de Produtos
    Route::get('/produtos-scan', [ProdutoController::class, 'index'])->name('produtos.index');
    Route::get('/produtos/etiquetas', [ProdutoController::class, 'gerarEtiquetas'])->name('produtos.etiquetas');
    Route::delete('/produtos/escaneado/{codigo_ean}', [ProdutoController::class, 'deletarScan'])->name('ean.deletar');
    Route::post('/produtos/ean/adicionar', [ProdutoController::class, 'adicionarEAN'])->name('ean.adicionar');
    Route::delete('/produtos/ean/limpar-todos', [ProdutoController::class, 'limparTodosCodigos'])->name('ean.limpar.todos');
    // Telas de Cadastro de Produtos
    Route::get('/cadastro-produtos', [CadastroProdutoController::class, 'index'])->name('cadastro-produtos.index');
    Route::get('/cadastro-produtos/create', [CadastroProdutoController::class, 'create'])->name('cadastro-produtos.create');
    Route::get('/cadastro-produtos/{produto}/edit', [CadastroProdutoController::class, 'edit'])->name('cadastro-produtos.edit');
    Route::put('/cadastro-produtos/{produto}', [CadastroProdutoController::class, 'update'])->name('cadastro-produtos.update');
    Route::post('/cadastro-produtos', [CadastroProdutoController::class, 'store'])->name('cadastro-produtos.store');
    Route::delete('/cadastro-produtos/{cadastroProduto}', [CadastroProdutoController::class, 'destroy'])->name('cadastro-produtos.destroy');
    Route::get('/cadastro-produtos/search', [CadastroProdutoController::class, 'search'])->name('cadastro-produtos.search');
});
