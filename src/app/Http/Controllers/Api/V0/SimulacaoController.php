<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class SimulacaoController extends Controller
{

    private function loadJsonData($file)
    {
        $path = storage_path("app/private/json/{$file}.json");
        
        if (!file_exists($path)) {
            throw new \Exception("Arquivo {$file}.json não encontrado");
        }
        
        $data = json_decode(file_get_contents($path), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erro ao decodificar JSON: " . json_last_error_msg());
        }
        
        return $data;
    }


    public function instituicoes()
    {
        try {
            $instituicoes = $this->loadJsonData('instituicoes');
            
            return response()->json($instituicoes);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao carregar instituições',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function convenios()
    {
        try {
            $convenios = $this->loadJsonData('convenios');
            
            return response()->json($convenios);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao carregar convênios',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function simulacao(Request $request)
    {
        try {
            $validated = $request->validate([
                'valor_emprestimo' => 'required|numeric|min:0',
                'instituicoes' => 'sometimes|array',
                'convenios' => 'sometimes|array',
                'parcela' => 'sometimes|integer|min:1'
            ]);

            $taxas = $this->loadJsonData('taxas_instituicoes');
            
            $filtered = array_filter($taxas, function($taxa) use ($validated) {
                if (isset($validated['instituicoes']) && 
                    !in_array($taxa['instituicao'], $validated['instituicoes'])) {
                    return false;
                }

                if (isset($validated['convenios']) && 
                    !in_array($taxa['convenio'], $validated['convenios'])) {
                    return false;
                }
                
                if (isset($validated['parcela']) && 
                    $taxa['parcelas'] != $validated['parcela']) {
                    return false;
                }
                
                return true;
            });

            $resultados = [];
            foreach ($filtered as $taxa) {
                $instituicao = $taxa['instituicao'];
                $valorParcela = round($validated['valor_emprestimo'] * $taxa['coeficiente'], 2);
                
                if (!isset($resultados[$instituicao])) {
                    $resultados[$instituicao] = [];
                }
                
                $resultados[$instituicao][] = [
                    'taxa' => $taxa['taxaJuros'],
                    'parcelas' => $taxa['parcelas'],
                    'valor_parcela' => $valorParcela,
                    'convenio' => $taxa['convenio'],
                    'coeficiente' => $taxa['coeficiente']
                ];
            }

            // Instituições ordenadas por numeros de parcelas (ascendente)
            foreach ($resultados as &$opcoes) {
                usort($opcoes, function($a, $b) {
                    return $a['parcelas'] <=> $b['parcelas'];
                });
            }

            return response()->json($resultados);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro na simulação',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}