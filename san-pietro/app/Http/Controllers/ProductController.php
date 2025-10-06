<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index()
    {
        $products = Product::orderBy('nome_commerciale')->paginate(15);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codice' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where(fn($q) =>
                    $q->where('company_id', auth()->user()->company_id)
                )
            ],
            'nome_scientifico' => 'required|string|max:255',
            'nome_commerciale' => 'required|string|max:255',
            'specie' => 'required|in:VONGOLE,COZZE,OSTRICHE,ALTRO',
            'pezzatura' => 'required|in:MICRO,PICCOLA,MEDIA,GROSSA,SUPER,SGRANATA,TRECCIA',
            'destinazione' => 'required|in:CONSUMO,REIMMERSIONE,DEPURAZIONE',
            'prezzo_base' => 'required|numeric|min:0',
            'unita_misura' => 'required|in:kg,pz,confezione',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['company_id'] = auth()->user()->company_id;

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Prodotto creato con successo.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'codice' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where(fn($q) =>
                    $q->where('company_id', auth()->user()->company_id)
                )->ignore($product->id)
            ],
            'nome_scientifico' => 'required|string|max:255',
            'nome_commerciale' => 'required|string|max:255',
            'specie' => 'required|in:VONGOLE,COZZE,OSTRICHE,ALTRO',
            'pezzatura' => 'required|in:MICRO,PICCOLA,MEDIA,GROSSA,SUPER,SGRANATA,TRECCIA',
            'destinazione' => 'required|in:CONSUMO,REIMMERSIONE,DEPURAZIONE',
            'prezzo_base' => 'required|numeric|min:0',
            'unita_misura' => 'required|in:kg,pz,confezione',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Prodotto aggiornato con successo.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Prodotto eliminato con successo.');
    }
}
