<?php

use App\Http\Controllers\PessoasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\veiculos_controller;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// CLientes
Route::middleware('auth')->group(function(){
    Route::get('/clientes', [PessoasController::class, 'index'])->name('Clientes.list');
    Route::post('/api/clientes/inserir', [PessoasController::class, 'inserirPessoa'])->name('Clientes.inserir');
    Route::post('/api/clientes/editar/{id}', [PessoasController::class,'editarPessoa'])->name('Clientes.editars');
});

//Veiculos
Route::middleware('auth')->group(function(){
    Route::get('/veiculos', [veiculos_controller::class, 'index'])->name('Veiculos.list');
    Route::post('/api/veiculos/inserir', [veiculos_controller::class, 'inserirVeiculo'])->name('Veiculos.inserir');
    Route::post('/api/veiculos/editar/{id}', [veiculos_controller::class,'editarPessoa'])->name('Veiculos.editar');
});

//Produtos
Route::middleware('auth')->group(function(){
    Route::get('/Produtos', [Produtoscontroller::class, 'index'])->name('Produtos.list');
    Route::post('/api/Produtos/inserir', [Produtoscontroller::class, 'inserirVeiculo'])->name('Produtos.inserir');
    Route::post('/api/Produtos/editar/{id}', [Produtoscontroller::class,'editarPessoa'])->name('Produtos.editar');
});

require __DIR__.'/auth.php';
