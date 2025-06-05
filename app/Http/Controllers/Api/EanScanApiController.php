<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EanScan;
use Illuminate\Support\Facades\Validator;

class EanScanApiController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigos_ean' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        // Separa os códigos por vírgula e remove espaços extras
        $codigos = array_filter(array_map('trim', explode(',', $request->codigos_ean)));

        $cadastrados = [];
        $duplicados = [];

        foreach ($codigos as $codigo) {
            if (!empty($codigo)) {
                // Verifica se o código já existe no banco
                $existe = EanScan::where('codigo_ean', $codigo)->exists();

                if (!$existe) {
                    $ean = EanScan::create(['codigo_ean' => $codigo]);
                    $cadastrados[] = $ean;
                } else {
                    $duplicados[] = $codigo;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Processamento concluído.',
            'novos' => $cadastrados,
            'duplicados' => $duplicados,
        ]);
    }

    public function limpar()
    {
        EanScan::truncate();

        return response()->json([
            'success' => true,
            'message' => 'Todos os códigos foram removidos com sucesso.'
        ]);
    }

    public function listar(Request $request)
    {
        $onlyEan = $request->boolean('only_ean', false);

        $dados = EanScan::all();

        return response()->json([
            'success' => true,
            'ean_codes' => $onlyEan ? $dados->pluck('codigo_ean') : $dados
        ]);
    }
}
