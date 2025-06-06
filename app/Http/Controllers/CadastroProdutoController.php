<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use Illuminate\Support\Facades\Storage;

class CadastroProdutoController extends Controller
{
    public function index(Request $request)
    {
        $fabricante = $request->input('fabricante');

        $query = Produto::query();

        if ($fabricante) {
            $query->where('fabricante', $fabricante);
        }

        //$cadastroProdutos = $query->orderBy('nome')->paginate(10);
        $cadastroProdutos = $query->orderBy('data_cadastro', 'desc')->paginate(10)->withQueryString();

        $fabricantes = Produto::select('fabricante')->distinct()->pluck('fabricante');

        return view('cadastro-produtos.index', compact('cadastroProdutos', 'fabricantes', 'fabricante'));
    }

    public function create()
    {
        return view('cadastro-produtos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo_ean' => 'required|unique:produtos,codigo_ean|string|max:255',
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'fabricante' => 'required|string|max:255',
            'ano_fabricacao' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'data_vencimento' => 'required|date',
            'valor' => 'required|numeric',
            'imagem' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'codigo_ean',
            'nome',
            'descricao',
            'fabricante',
            'ano_fabricacao',
            'data_vencimento',
            'valor',
        ]);

        $data['data_cadastro'] = now();

        if ($request->hasFile('imagem')) {
            //$path = $request->file('imagem')->store('cadastro-produtos', 'public');
            //$data['imagem'] = $path;

            $path = $request->file('imagem')->store('produtos', 'public');
            $data['imagem'] = $path;
        }

        Produto::create($data);

        return redirect()->route('cadastro-produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function destroy(Produto $cadastroProduto)
    {
        if ($cadastroProduto->imagem) {
            Storage::disk('public')->delete($cadastroProduto->imagem);
        }

        $cadastroProduto->delete();

        return redirect()->route('cadastro-produtos.index')->with('success', 'Produto deletado com sucesso!');
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('query');
        $fabricante = $request->input('fabricante', '');

        $query = Produto::query();

        if ($searchTerm) {
            $query->where('codigo_ean', 'like', "%{$searchTerm}%")
                ->orWhere('nome', 'like', "%{$searchTerm}%");
        }

        if ($fabricante) {
            $query->where('fabricante', $fabricante);
        }

        $cadastroProdutos = $query->paginate(10);

        $fabricantes = Produto::select('fabricante')->distinct()->pluck('fabricante');

        return view('cadastro-produtos.index', compact('cadastroProdutos', 'fabricantes', 'fabricante'));
    }

    public function edit(Produto $produto)
    {
        return view('cadastro-produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $rules = [
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'fabricante' => 'required|string|max:255',
            'ano_fabricacao' => 'required|integer|min:1900|max:' . date('Y'),
            'data_vencimento' => 'required|date',
            'valor' => 'required|numeric',
            'data_cadastro' => 'nullable|date',
        ];

        if (!$produto->imagem) {
            $rules['imagem'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        } else {
            $rules['imagem'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('cadastro-produtos', 'public');
        }

        unset($validated['codigo_ean']);

        $produto->update($validated);

        return redirect()->route('cadastro-produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }
    }
