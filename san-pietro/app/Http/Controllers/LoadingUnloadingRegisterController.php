<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LoadingUnloadingRegister;
use App\Models\Product;
use App\Models\TransportDocument;
use Illuminate\Http\Request;

class LoadingUnloadingRegisterController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(LoadingUnloadingRegister::class, 'loading_unloading');
    }

    public function index()
    {
        $user = auth()->user();
        $query = LoadingUnloadingRegister::with(['product', 'transportDocument', 'company']);

        // Filtra in base al ruolo
        if ($user->hasRole('SUPER_ADMIN')) {
            // SUPER_ADMIN vede tutto
        } elseif ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            // San Pietro vede i propri registri + quelli delle aziende invitate
            $query->where(function($q) use ($user) {
                $q->where('company_id', $user->company_id)
                  ->orWhereHas('company', function($companyQuery) use ($user) {
                      $companyQuery->where('parent_company_id', $user->company_id);
                  });
            });
        } else {
            // Altri vedono solo i propri registri
            $query->where('company_id', $user->company_id);
        }

        $registers = $query->orderBy('data_operazione', 'desc')->paginate(15);

        return view('loading-unloading.index', compact('registers'));
    }

    public function create()
    {
        $user = auth()->user();

        // Filtra prodotti per azienda
        $products = Product::where('is_active', true)
            ->where('company_id', $user->company_id)
            ->orderBy('nome_commerciale')
            ->get();

        // Filtra documenti di trasporto per azienda
        $transportDocuments = TransportDocument::where('company_id', $user->company_id)
            ->orderBy('data_documento', 'desc')
            ->take(50)
            ->get();

        return view('loading-unloading.create', compact('products', 'transportDocuments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_operazione' => 'required|date',
            'tipo_operazione' => 'required|in:CARICO,SCARICO',
            'product_id' => 'required|exists:products,id',
            'lotto' => 'nullable|string|max:255',
            'kg_reimmersione' => 'nullable|numeric|min:0',
            'kg_piccola' => 'nullable|numeric|min:0',
            'kg_media' => 'nullable|numeric|min:0',
            'kg_grossa' => 'nullable|numeric|min:0',
            'kg_granchio' => 'nullable|numeric|min:0',
            'provenienza_destinazione' => 'nullable|string|max:500',
            'transport_document_id' => 'nullable|exists:transport_documents,id',
            'note' => 'nullable|string',
        ]);

        // Imposta valori di default per campi null
        foreach (['kg_reimmersione', 'kg_piccola', 'kg_media', 'kg_grossa', 'kg_granchio'] as $field) {
            if (!isset($validated[$field])) {
                $validated[$field] = 0;
            }
        }

        $validated['company_id'] = auth()->user()->company_id;
        LoadingUnloadingRegister::create($validated);

        return redirect()->route('loading-unloading.index')
            ->with('success', 'Registro di carico/scarico creato con successo.');
    }

    public function show(LoadingUnloadingRegister $loading_unloading)
    {
        $loading_unloading->load(['product', 'transportDocument', 'company']);
        return view('loading-unloading.show', ['loadingUnloadingRegister' => $loading_unloading]);
    }

    public function edit(LoadingUnloadingRegister $loading_unloading)
    {
        $user = auth()->user();

        // Filtra prodotti per azienda
        $products = Product::where('is_active', true)
            ->where('company_id', $user->company_id)
            ->orderBy('nome_commerciale')
            ->get();

        // Filtra documenti di trasporto per azienda
        $transportDocuments = TransportDocument::where('company_id', $user->company_id)
            ->orderBy('data_documento', 'desc')
            ->take(50)
            ->get();

        return view('loading-unloading.edit', [
            'loadingUnloadingRegister' => $loading_unloading,
            'products' => $products,
            'transportDocuments' => $transportDocuments
        ]);
    }

    public function update(Request $request, LoadingUnloadingRegister $loading_unloading)
    {
        $validated = $request->validate([
            'data_operazione' => 'required|date',
            'tipo_operazione' => 'required|in:CARICO,SCARICO',
            'product_id' => 'required|exists:products,id',
            'lotto' => 'nullable|string|max:255',
            'kg_reimmersione' => 'nullable|numeric|min:0',
            'kg_piccola' => 'nullable|numeric|min:0',
            'kg_media' => 'nullable|numeric|min:0',
            'kg_grossa' => 'nullable|numeric|min:0',
            'kg_granchio' => 'nullable|numeric|min:0',
            'provenienza_destinazione' => 'nullable|string|max:500',
            'transport_document_id' => 'nullable|exists:transport_documents,id',
            'note' => 'nullable|string',
        ]);

        // Imposta valori di default per campi null
        foreach (['kg_reimmersione', 'kg_piccola', 'kg_media', 'kg_grossa', 'kg_granchio'] as $field) {
            if (!isset($validated[$field])) {
                $validated[$field] = 0;
            }
        }

        $loading_unloading->update($validated);

        return redirect()->route('loading-unloading.index')
            ->with('success', 'Registro di carico/scarico aggiornato con successo.');
    }

    public function destroy(LoadingUnloadingRegister $loading_unloading)
    {
        $loading_unloading->delete();

        return redirect()->route('loading-unloading.index')
            ->with('success', 'Registro di carico/scarico eliminato con successo.');
    }
}
