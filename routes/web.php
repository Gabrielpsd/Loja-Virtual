<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\PessoasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicosController;
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
    Route::post('/api/veiculos/editar/{id}', [veiculos_controller::class,'editarVeiculo'])->name('Veiculos.editar');
});

//Servicos
Route::middleware('auth')->group(function(){
    Route::get('/Servicos', [ServicosController::class, 'index'])->name('Servicos.list');
    Route::post('/api/servicos/inserir', [ServicosController::class, 'inserirServico'])->name('Servicos.inserir');
    Route::post('/api/servicos/editar/{id}', [ServicosController::class,'editarServico'])->name('Servicos.editar');
});

//Ordem de servico
Route::middleware('auth')->group(function(){
    Route::get('/OrdemServico', [OrdemServicoController::class, 'index'])->name('OrdemServico.list');
    Route::post('/api/OrdemServico/inserir', [OrdemServicoController::class, 'inserirOrdemServico'])->name('OrdemServico.inserir');
    Route::post('/api/OrdemServico/editar/{id}', [OrdemServicoController::class,'editarOrdemServico'])->name('OrdemServico.editar');
});

//Dashboard
Route::middleware('auth')->group(function(){
    Route::post('/api/dashboard/vendasporsexo', [DashboardController::class, 'vendasPorSexo'])->name('Dashboard.vendasPorSexo');
    Route::post('/api/dashboard/quantidadevendasporperiodo', [DashboardController::class, 'quantidadeVendasPorPeriodo'])->name('Dashboard.quantidadeVendasPorPeriodo');
    Route::post('/api/dashboard/totalporperiodo', [DashboardController::class, 'totalPorPeriodo'])->name('Dashboard.totalPorPeriodo');
    Route::post('/api/dashboard/totalidadeporsexo', [DashboardController::class, 'totalIdadePorSexo'])->name('Dashboard.totalIdadePorSexo');
    Route::post('/api/dashboard/totalvalorpormarca', [DashboardController::class, 'totalValorPorMarca'])->name('Dashboard.totalValorPorMarca');
    Route::post('/api/dashboard/vendasporperiodo', [DashboardController::class, 'VendasPorPeriodo'])->name('Dashboard.VendasPorPeriodo');
    Route::post('/api/dashboard/totalservicosporperiodo', [DashboardController::class, 'totalServicosPorPeriodo'])->name('Dashboard.totalServicosPorPeriodo');
});

require __DIR__.'/auth.php';
