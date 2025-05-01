<?php

use App\Http\Controllers\Api\V0\SimulacaoController;
use Illuminate\Support\Facades\Route;

Route::get('/instituicoes', [SimulacaoController::class, 'instituicoes']);