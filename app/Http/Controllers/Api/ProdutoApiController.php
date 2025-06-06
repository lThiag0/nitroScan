<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoApiController extends Controller
{
    public function index(Request $request)
    {
        // Verifica se a requisição quer apenas os códigos EAN
        if ($request->has('only_ean') && $request->only_ean == 'true') {
            $codigos = Produto::whereIn('codigo_ean', function ($query) {
                $query->select('codigo_ean')->from('ean_scans');
            })->pluck('codigo_ean');

            return response()->json([
                'success' => true,
                'ean_codes' => $codigos,
            ]);
        }

        // Caso contrário, retorna os produtos com todos os dados
        $produtos = Produto::whereIn('codigo_ean', function ($query) {
            $query->select('codigo_ean')->from('ean_scans');
        })->get();

        $produtosFormatados = $produtos->map(function ($produto) {
            return [
                'codigo_ean'       => $produto->codigo_ean,
                'nome'             => $produto->nome,
                'descricao'        => $produto->descricao,
                'fabricante'       => $produto->fabricante,
                'ano_fabricacao'   => $produto->ano_fabricacao,
                'data_vencimento'  => $produto->data_vencimento?->format('Y-m-d'),
                'valor'            => $produto->valor,
                'data_cadastro'    => $produto->data_cadastro?->format('Y-m-d H:i:s'),
                'imagem_url'       => $produto->imagem ? asset('storage/' . $produto->imagem) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $produtosFormatados,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'codigo_ean'       => 'required|string|unique:produtos,codigo_ean',
                'nome'             => 'required|string',
                'descricao'        => 'nullable|string',
                'fabricante'       => 'nullable|string',
                'ano_fabricacao'   => 'nullable|integer',
                'data_vencimento'  => 'nullable|date',
                'valor'            => 'required|numeric',
                'imagem'           => 'nullable|image|max:10240', // 10MB
            ]);

            if ($request->hasFile('imagem')) {
                if (!$request->file('imagem')->isValid()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Erro ao fazer upload da imagem. Arquivo inválido ou corrompido.',
                    ], 422);
                }

                $path = $request->file('imagem')->store('produtos', 'public');
                $validated['imagem'] = $path;
            }

            $produto = Produto::create($validated);

            return response()->json([
                'success' => true,
                'data' => $produto,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Erro de validação: ' . collect($e->errors())->flatten()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showByEan($codigo_ean)
    {
        $produto = Produto::where('codigo_ean', $codigo_ean)->first();

        if (!$produto) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'codigo_ean'      => $produto->codigo_ean,
                'nome'            => $produto->nome,
                'descricao'       => $produto->descricao,
                'fabricante'      => $produto->fabricante,
                'ano_fabricacao'  => $produto->ano_fabricacao,
                'data_vencimento' => $produto->data_vencimento?->format('Y-m-d'),
                'valor'           => $produto->valor,
                'data_cadastro'   => $produto->data_cadastro?->format('Y-m-d H:i:s'),
                'imagem_url'      => $produto->imagem ? asset('storage/' . $produto->imagem) : null,
            ]
        ]);
    }

    public function update(Request $request, $codigo_ean)
    {
        $produto = Produto::where('codigo_ean', $codigo_ean)->first();

        if (!$produto) {
            return response()->json(['erro' => 'Produto não encontrado.'], 404);
        }

        $request->validate([
            'nome' => 'required|string',
            'descricao' => 'nullable|string',
            'fabricante' => 'nullable|string',
            'ano_fabricacao' => 'nullable|integer',
            'data_vencimento' => 'nullable|date',
            'valor' => 'required|numeric',
            'imagem' => 'nullable|image|max:10240', // 10MB
        ]);

        $produto->nome = $request->input('nome');
        $produto->descricao = $request->input('descricao');
        $produto->fabricante = $request->input('fabricante');
        $produto->ano_fabricacao = $request->input('ano_fabricacao');
        $produto->data_vencimento = $request->input('data_vencimento');
        $produto->valor = $request->input('valor');

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $path = $imagem->store('produtos', 'public');
            $produto->imagem = $path;
        }

        $produto->save();

        return response()->json(['mensagem' => 'Produto atualizado com sucesso.', 'produto' => $produto], 200);
    }

    public function destroy($codigo_ean)
    {
        $produto = Produto::where('codigo_ean', $codigo_ean)->firstOrFail();
        $produto->delete();

        return response()->json(['success' => true, 'message' => 'Produto excluído com sucesso.']);
    }

}
