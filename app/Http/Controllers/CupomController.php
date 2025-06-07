<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $cupons = Cupom::all();
        return view('cupons.index', compact('cupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cupons.create_edit', ['cupom' => new Cupom()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|unique:cupons,codigo|max:50',
            'tipo' => 'required|in:fixo,percentual',
            'valor' => 'required|numeric|min:0',
            'min_subtotal' => 'required|numeric|min:0',
            'validade_inicio' => 'nullable|date',
            'validade_fim' => 'nullable|date|after_or_equal:validade_inicio',
        ]);

        Cupom::create($request->all());
        return redirect()->route('cupons.index')
                         ->with('success', 'Cupom criado com sucesso.');
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
         $cupom = Cupom::findOrFail($id);
        return view('cupons.create_edit', compact('cupom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $cupom = Cupom::findOrFail($id);
        $request->validate([
            'codigo' => 'required|string|max:50|unique:cupons,codigo,' . $cupom->id,
            'tipo' => 'required|in:fixo,percentual',
            'valor' => 'required|numeric|min:0',
            'min_subtotal' => 'required|numeric|min:0',
            'validade_inicio' => 'nullable|date',
            'validade_fim' => 'nullable|date|after_or_equal:validade_inicio',
        ]);

        $cupom->update($request->all());
        return redirect()->route('cupons.index')
                         ->with('success', 'Cupom atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cupom = Cupom::findOrFail($id);
        $cupom->delete();
        return redirect()->route('cupons.index')
                         ->with('success', 'Cupom removido.');
    }
}
