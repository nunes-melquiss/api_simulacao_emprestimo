<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v0')->group(function () {
    Route::get('/instituicoes', [\App\Http\Controllers\Api\V0\SimulacaoController::class, 'instituicoes']);
    Route::get('/convenios', [\App\Http\Controllers\Api\V0\SimulacaoController::class, 'convenios']);
    Route::post('/simulacao', [\App\Http\Controllers\Api\V0\SimulacaoController::class, 'simulacao']);
});