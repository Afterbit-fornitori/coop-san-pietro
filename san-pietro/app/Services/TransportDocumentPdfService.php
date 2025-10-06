<?php

namespace App\Services;

use App\Models\TransportDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class TransportDocumentPdfService
{
    /**
     * Genera PDF per il documento di trasporto (DDT/DTN/DDR)
     */
    public function generatePdf(TransportDocument $document): \Barryvdh\DomPDF\PDF
    {
        // Carica il documento con tutte le relazioni necessarie
        $document->load([
            'client',
            'member',
            'productionZone',
            'items.product',
            'company'
        ]);

        // Dati per il PDF
        $data = [
            'document' => $document,
            'company' => $document->company,
            'client' => $document->client,
            'member' => $document->member,
            'productionZone' => $document->productionZone,
            'items' => $document->items,
            'totals' => $this->calculateTotals($document),
        ];

        // Genera PDF usando la view dedicata
        $pdf = Pdf::loadView('pdf.transport-document', $data);

        // Configurazioni PDF
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('enable_html5_parser', true);
        $pdf->setOption('enable_remote', false);

        return $pdf;
    }

    /**
     * Calcola i totali del documento
     */
    private function calculateTotals(TransportDocument $document): array
    {
        $totaleImponibile = $document->items->sum('totale');
        $iva = $totaleImponibile * 0.10; // IVA 10% (configurabile)
        $totaleDocumento = $totaleImponibile + $iva;

        return [
            'totale_imponibile' => number_format($totaleImponibile, 2, ',', '.'),
            'iva' => number_format($iva, 2, ',', '.'),
            'totale_documento' => number_format($totaleDocumento, 2, ',', '.'),
            'totale_kg' => number_format($document->items->sum('quantita_kg'), 2, ',', '.'),
            'totale_colli' => $document->items->sum('numero_colli'),
        ];
    }

    /**
     * Scarica il PDF con nome file formattato
     */
    public function downloadPdf(TransportDocument $document, string $filename = null): \Illuminate\Http\Response
    {
        $pdf = $this->generatePdf($document);

        // Nome file default: {TIPO}_{SERIE}_{NUMERO}_{ANNO}.pdf
        if (!$filename) {
            $filename = sprintf(
                '%s_%s_%s_%s.pdf',
                $document->tipo_documento,
                $document->serie,
                str_pad($document->numero, 4, '0', STR_PAD_LEFT),
                $document->anno
            );
        }

        return $pdf->download($filename);
    }

    /**
     * Visualizza il PDF inline nel browser
     */
    public function streamPdf(TransportDocument $document): \Illuminate\Http\Response
    {
        $pdf = $this->generatePdf($document);

        $filename = sprintf(
            '%s_%s_%s_%s.pdf',
            $document->tipo_documento,
            $document->serie,
            str_pad($document->numero, 4, '0', STR_PAD_LEFT),
            $document->anno
        );

        return $pdf->stream($filename);
    }

    /**
     * Salva il PDF su storage
     */
    public function savePdf(TransportDocument $document, string $path = null): string
    {
        $pdf = $this->generatePdf($document);

        // Path default: transport-documents/{ANNO}/{TIPO}/{FILENAME}
        if (!$path) {
            $filename = sprintf(
                '%s_%s_%s_%s.pdf',
                $document->tipo_documento,
                $document->serie,
                str_pad($document->numero, 4, '0', STR_PAD_LEFT),
                $document->anno
            );

            $path = sprintf(
                'transport-documents/%s/%s/%s',
                $document->anno,
                $document->tipo_documento,
                $filename
            );
        }

        // Salva su storage (disk public o s3)
        \Storage::put($path, $pdf->output());

        return $path;
    }
}
