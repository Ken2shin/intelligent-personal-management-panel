<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Tareas\Tareas;
use App\Livewire\Habitos\Habitos;
use App\Livewire\Finanzas\Finanzas;
use App\Livewire\Notas\Notas;

// Rutas públicas
Route::view('/', 'welcome')->name('welcome');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/tareas', Tareas::class)->name('tareas');
    Route::get('/habitos', Habitos::class)->name('habitos');
    Route::get('/finanzas', Finanzas::class)->name('finanzas');
    Route::get('/notas', Notas::class)->name('notas');
});

// Auth routes (Laravel Breeze)
require __DIR__.'/auth.php';