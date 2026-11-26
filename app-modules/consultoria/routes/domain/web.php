<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::view('/', 'consultoria::home')->name('consultoria.home');
Route::view('/licitacoes', 'consultoria::licitacoes')->name('consultoria.licitacoes');
