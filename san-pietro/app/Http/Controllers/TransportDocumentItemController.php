<?php

namespace App\Http\Controllers;

use App\Models\TransportDocumentItem;
use App\Models\TransportDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransportDocumentItemController extends Controller
{
    /**
     * Store a newly created resource in storage (AJAX).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transport_document_id' => 'required|exists:transport_documents,id',
            'product_id' => 'required|exists:products,id',
            'quantita_kg' => 'required|numeric|min:0',
            'numero_colli' => 'required|integer|min:0',
            'prezzo_unitario' => 'required|numeric|min:0',
        ]);

        // Verifica che l'utente possa accedere al documento
        $document = TransportDocument::findOrFail($validated['transport_document_id']);
        $this->authorize('update', $document);

        // Crea l'item (il totale viene calcolato automaticamente nel model)
        $item = TransportDocumentItem::create($validated);

        // Il metodo boot() del model TransportDocumentItem aggiorna automaticamente i totali del documento

        return response()->json([
            'success' => true,
            'message' => 'Prodotto aggiunto con successo.',
            'item' => $item->load('product')
        ]);
    }

    /**
     * Remove the specified resource from storage (AJAX).
     */
    public function destroy(TransportDocumentItem $transportDocumentItem)
    {
        // Verifica autorizzazione
        $this->authorize('update', $transportDocumentItem->transportDocument);

        $transportDocumentItem->delete();

        // Il metodo boot() del model TransportDocumentItem aggiorna automaticamente i totali del documento

        return response()->json([
            'success' => true,
            'message' => 'Prodotto eliminato con successo.'
        ]);
    }
}
