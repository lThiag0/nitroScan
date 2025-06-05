<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\EanScan;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $aba = $request->get('aba', 'ean');

        // Ordenar pelo mais recente (created_at decrescente) e paginar
        $codigosEscaneados = EanScan::orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page_codigos');

        return view('produtos.index', compact('codigosEscaneados', 'aba'));
    }

    // API para retornar detalhes do produto via AJAX
    public function detalhesProduto($codigoEAN)
    {
        $produto = Produto::where('codigo_ean', $codigoEAN)->first();

        if (!$produto) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        return response()->json([
            'codigo_ean' => $produto->codigo_ean,
            'nome' => $produto->nome,
            'descricao' => $produto->descricao,
            'fabricante' => $produto->fabricante,
            'ano_fabricacao' => $produto->ano_fabricacao,
            'data_vencimento' => optional($produto->data_vencimento)->format('Y-m-d'),
            'valor' => number_format($produto->valor, 2, '.', ''),
            'data_cadastro' => $produto->data_cadastro ? $produto->data_cadastro->format('d/m/Y H:i') : null,
            'imagem' => $produto->imagem ? asset('storage/' . $produto->imagem) : null,
        ]);
    }

    public function apiListarEans()
    {
        $codigos = EanScan::orderBy('created_at', 'desc')->pluck('codigo_ean');
        return response()->json($codigos);
    }

    public function gerarEtiquetas()
    {
        $codigosEscaneados = EanScan::pluck('codigo_ean')->unique();

        if ($codigosEscaneados->isEmpty()) {
            return redirect()->back()->with('error', 'Nenhum código EAN escaneado encontrado.');
        }

        $produtos = Produto::whereIn('codigo_ean', $codigosEscaneados)->get();

        if ($produtos->isEmpty()) {
            return redirect()->back()->with('error', 'Nenhum produto correspondente encontrado na base de dados.');
        }

        $pdf = Pdf::loadView('produtos.etiquetas', compact('produtos'));

        return $pdf->download('etiquetas_produtos.pdf')
                ->withHeaders(['Content-Type' => 'application/pdf']);
    }

    public function adicionarEAN(Request $request)
    {
        $request->validate([
            'codigo_ean' => ['required', 'string', 'regex:/^\d{1,14}$/', 'unique:ean_scans,codigo_ean'],
        ], [
            'codigo_ean.required' => 'O código EAN é obrigatório.',
            'codigo_ean.regex' => 'O código EAN deve conter apenas números com até 14 dígitos.',
            'codigo_ean.unique' => 'Este código EAN já foi adicionado.',
        ]);

        EanScan::create([
            'codigo_ean' => $request->codigo_ean,
        ]);

        return redirect()->route('produtos.index')->with('success', 'Código EAN adicionado com sucesso.');
    }

    public function limparTodosCodigos()
    {
        EanScan::truncate();
        return redirect()->back()->with('success', 'Todos os códigos EAN foram removidos com sucesso.');
    }

    public function deletarScan($codigo_ean)
    {
        EanScan::where('codigo_ean', $codigo_ean)->delete();

        return redirect()->back()->with('success', 'Código EAN removido com sucesso.');
    }
}
