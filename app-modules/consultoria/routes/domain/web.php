<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use TresPontosTech\Consultoria\Http\Controllers\ContatoController;
use TresPontosTech\Consultoria\Http\Controllers\RobotsController;

Route::view('/', 'consultoria::home')->name('consultoria.home');
Route::view('/licitacoes', 'consultoria::licitacoes')->name('consultoria.licitacoes');
Route::get('/robots.txt', RobotsController::class)->name('consultoria.robots');

Route::post('/contato', ContatoController::class)
    ->middleware('throttle:consultoria-contato')
    ->name('consultoria.contato');

Route::fallback(fn () => response()->view('consultoria::404', [], 404));
