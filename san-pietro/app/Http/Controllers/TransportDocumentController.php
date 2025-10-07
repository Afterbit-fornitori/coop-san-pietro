<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransportDocument;
use App\Models\Client;
use App\Models\Member;
use App\Models\ProductionZone;
use App\Services\TransportDocumentPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransportDocumentController extends Controller
{
    protected $pdfService;

    public function __construct(TransportDocumentPdfService $pdfService)
    {
        $this->pdfService = $pdfService;
        $this->authorizeResource(TransportDocument::class, 'transport_document');
    }

    public function index()
    {
        $transportDocuments = TransportDocument::with(['client', 'member', 'productionZone'])
            ->orderBy('data_documento', 'desc')
            ->paginate(15);

        return view('transport-documents.index', compact('transportDocuments'));
    }

    public function create()
    {
        $clients = Client::orderBy('business_name')->get();
        $members = Member::orderBy('last_name')->orderBy('first_name')->get();
        $productionZones = ProductionZone::where('is_active', true)->orderBy('nome')->get();

        return view('transport-documents.create', compact('clients', 'members', 'productionZones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'member_id' => 'nullable|exists:members,id',
            'production_zone_id' => 'nullable|exists:production_zones,id',
            'serie' => 'required|string|max:10',
            'data_documento' => 'required|date',
            'ora_partenza' => 'nullable|string',
            'data_raccolta' => 'nullable|date',
            'tipo_documento' => 'required|in:DDT,DTN,DDR',
            'causale_trasporto' => 'required|string|max:255',
            'mezzo_trasporto' => 'nullable|string|max:255',
            'annotazioni' => 'nullable|string',
        ]);

        // Transaction con lock per numerazione progressiva
        $transportDocument = DB::transaction(function () use ($validated) {
            $year = date('Y', strtotime($validated['data_documento']));
            $companyId = auth()->user()->company_id;

            // Lock per evitare race condition sulla numerazione
            $lastNumber = TransportDocument::where('company_id', $companyId)
                ->where('serie', $validated['serie'])
                ->where('anno', $year)
                ->lockForUpdate()
                ->max('numero') ?? 0;

            $validated['numero'] = $lastNumber + 1;
            $validated['anno'] = $year;
            $validated['company_id'] = $companyId;

            return TransportDocument::create($validated);
        });

        return redirect()->route('transport-documents.edit', $transportDocument)
            ->with('success', 'Documento di trasporto creato con successo. Ora puoi aggiungere i prodotti.');
    }

    public function show(TransportDocument $transportDocument)
    {
        $transportDocument->load(['client', 'member', 'productionZone', 'items.product']);
        return view('transport-documents.show', compact('transportDocument'));
    }

    public function edit(TransportDocument $transportDocument)
    {
        $clients = Client::orderBy('business_name')->get();
        $members = Member::orderBy('last_name')->orderBy('first_name')->get();
        $productionZones = ProductionZone::where('is_active', true)->orderBy('nome')->get();

        return view('transport-documents.edit', compact('transportDocument', 'clients', 'members', 'productionZones'));
    }

    public function update(Request $request, TransportDocument $transportDocument)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'member_id' => 'nullable|exists:members,id',
            'production_zone_id' => 'nullable|exists:production_zones,id',
            'data_documento' => 'required|date',
            'ora_partenza' => 'nullable|string',
            'data_raccolta' => 'nullable|date',
            'tipo_documento' => 'required|in:DDT,DTN,DDR',
            'causale_trasporto' => 'required|string|max:255',
            'mezzo_trasporto' => 'nullable|string|max:255',
            'annotazioni' => 'nullable|string',
        ]);

        $transportDocument->update($validated);

        return redirect()->route('transport-documents.index')
            ->with('success', 'Documento di trasporto aggiornato con successo.');
    }

    public function destroy(TransportDocument $transportDocument)
    {
        $transportDocument->delete();

        return redirect()->route('transport-documents.index')
            ->with('success', 'Documento di trasporto eliminato con successo.');
    }

    /**
     * Scarica il PDF del documento di trasporto
     */
    public function downloadPdf(TransportDocument $transportDocument)
    {
        $this->authorize('view', $transportDocument);

        return $this->pdfService->downloadPdf($transportDocument);
    }

    /**
     * Visualizza il PDF del documento di trasporto inline
     */
    public function viewPdf(TransportDocument $transportDocument)
    {
        $this->authorize('view', $transportDocument);

        return $this->pdfService->streamPdf($transportDocument);
    }
}
