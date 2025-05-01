<?php

use App\Http\Controllers\Api\V0\SimulacaoController;
use Illuminate\Support\Facades\Route;

Route::post('/simulacao', [SimulacaoController::class, 'simulacao']);