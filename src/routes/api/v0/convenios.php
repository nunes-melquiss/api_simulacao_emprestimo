<?php

use App\Http\Controllers\Api\V0\SimulacaoController;
use Illuminate\Support\Facades\Route;

Route::get('/convenios', [SimulacaoController::class, 'convenios']);