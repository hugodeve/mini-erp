<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Estoque;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produtos = Produto::with('estoques')->get();
        return view('produtos.index', compact('produtos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produtos.create_edit', [
            'produto' => new Produto(),
            'estoques' => collect(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            // variacoes enviado como array de strings
            'variacoes' => 'nullable|array',
            'variacoes.*' => 'string|max:100',
            'estoques' => 'required|array',
            'estoques.*.variacao' => 'nullable|string|max:100',
            'estoques.*.quantidade' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $produto = Produto::create([
                'nome' => $request->nome,
                'preco' => $request->preco,
                'variacoes' => $request->variacoes ?? null,
            ]);

            
            // Criar estoques associados
            foreach ($request->estoques as $item) {
                Estoque::create([
                    'produto_id' => $produto->id,
                    'variacao'   => $item['variacao'] ?? null,
                    'quantidade' => $item['quantidade'],
                ]);
            }
        });

        return redirect()->route('produtos.index')
                         ->with('success', 'Produto criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $produto = Produto::findOrFail($id);
        $estoques = $produto->estoques;
        return view('produtos.create_edit', compact('produto', 'estoques'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'variacoes' => 'nullable|array',
            'variacoes.*' => 'string|max:100',
            'estoques' => 'required|array',
            'estoques.*.id' => 'nullable|exists:estoque,id',
            'estoques.*.variacao' => 'nullable|string|max:100',
            'estoques.*.quantidade' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {
            $produto = Produto::findOrFail($id);
            $produto->update([
                'nome' => $request->nome,
                'preco' => $request->preco,
                'variacoes' => $request->variacoes ?? null,
            ]);

            // Atualiza estoques existentes ou cria novos
            $existingIds = $produto->estoques()->pluck('id')->toArray();
            $sentIds = [];

            foreach ($request->estoques as $item) {
                if (isset($item['id']) && in_array($item['id'], $existingIds)) {
                    // Atualiza registro existente
                    $estoque = Estoque::findOrFail($item['id']);
                    $estoque->update([
                        'variacao' => $item['variacao'] ?? null,
                        'quantidade' => $item['quantidade'],
                    ]);
                    $sentIds[] = $item['id'];
                } else {
                    // Cria novo estoque
                    $novo = Estoque::create([
                        'produto_id' => $produto->id,
                        'variacao' => $item['variacao'] ?? null,
                        'quantidade' => $item['quantidade'],
                    ]);
                    $sentIds[] = $novo->id;
                }
            }

            // Remover estoques que não vieram na requisição (opcional)
            $idsToRemove = array_diff($existingIds, $sentIds);
            if (!empty($idsToRemove)) {
                Estoque::whereIn('id', $idsToRemove)->delete();
            }
        });

        return redirect()->route('produtos.index')
                         ->with('success', 'Produto atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();
        return redirect()->route('produtos.index')
                         ->with('success', 'Produto removido.');
    }
}
